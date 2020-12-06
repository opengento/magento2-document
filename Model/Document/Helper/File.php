<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Document\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\File\Uploader;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Glob;
use Opengento\Document\Api\Data\DocumentInterface;
use Opengento\Document\Api\Data\DocumentTypeInterface;
use Psr\Log\LoggerInterface;
use function array_slice;
use function basename;
use function dirname;
use function implode;
use function ltrim;
use function mb_str_split;
use function pathinfo;
use function preg_replace;
use function strtolower;
use function trim;
use const DIRECTORY_SEPARATOR;
use const GLOB_BRACE;
use const PATHINFO_FILENAME;

/**
 * @api
 */
final class File
{
    public const DOCUMENT_PATH = 'document' . DIRECTORY_SEPARATOR;
    public const FILE_PATH = self::DOCUMENT_PATH . 'file' . DIRECTORY_SEPARATOR;
    public const IMAGE_PATH = self::DOCUMENT_PATH . 'image' . DIRECTORY_SEPARATOR;
    public const IMAGE_CACHE_PATH = self::IMAGE_PATH . 'cache' . DIRECTORY_SEPARATOR;
    public const TMP_PATH = self::DOCUMENT_PATH . 'tmp' . DIRECTORY_SEPARATOR;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var bool
     */
    private $allowRenameFiles;

    public function __construct(
        Filesystem $filesystem,
        LoggerInterface $logger,
        bool $allowRenameFiles = true
    ) {
        $this->filesystem = $filesystem;
        $this->logger = $logger;
        $this->allowRenameFiles = $allowRenameFiles;
    }

    public function getFileDestPath(DocumentTypeInterface $documentType, string $filePath): string
    {
        return $this->resolveDestFilePath($documentType, $filePath, self::FILE_PATH);
    }

    public function getImageDestPath(DocumentTypeInterface $documentType, string $filePath): string
    {
        return $this->resolveDestFilePath($documentType, $filePath, self::IMAGE_PATH);
    }

    public function getFilePath(DocumentInterface $document): string
    {
        return $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)
            ->getAbsolutePath($document->getFilePath() . DIRECTORY_SEPARATOR . $document->getFileName());
    }

    public function getImagePath(DocumentInterface $document): string
    {
        return $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath($document->getImageFileName());
    }

    public function getRelativeFilePath(string $filePath): string
    {
        return $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->getRelativePath($filePath);
    }

    public function lookupFiles(DocumentTypeInterface $documentType, ?int $flags = null): array
    {
        $sourcePath = rtrim($this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath(
            self::FILE_PATH . ltrim($documentType->getFileSourcePath(), DIRECTORY_SEPARATOR)
        ), DIRECTORY_SEPARATOR);

        return Glob::glob(
            $sourcePath . DIRECTORY_SEPARATOR . ltrim($documentType->getFilePattern(), DIRECTORY_SEPARATOR),
            $flags | GLOB_BRACE
        );
    }

    /**
     * @inheritdoc
     * @throws FileSystemException
     */
    public function moveFile(string $filePath, string $destFilePath): void
    {
        $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA)->renameFile($filePath, $destFilePath);
    }

    public function deleteFile(string $filePath): bool
    {
        try {
            $directoryWrite = $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA);
            if ($filePath && $directoryWrite->isFile($filePath)) {
                return $directoryWrite->delete($filePath);
            }
        } catch (FileSystemException $e) {
            $this->logger->error($e->getLogMessage(), $e->getTrace());

            return false;
        }
    }

    private function resolveFileSubPath(DocumentTypeInterface $documentType, string $fileName): string
    {
        return implode(
                DIRECTORY_SEPARATOR,
                array_slice(
                    (array) mb_str_split(strtolower(preg_replace('/\W+/', '', pathinfo($fileName, PATHINFO_FILENAME)))),
                    0,
                    $documentType->getSubPathLength()
                )
            ) . DIRECTORY_SEPARATOR . $fileName;
    }

    private function resolveDestFilePath(
        DocumentTypeInterface $documentType,
        string $filePath,
        string $destPath
    ): string {
        $destFilePath = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath(
            $destPath . trim($documentType->getFileDestPath(), DIRECTORY_SEPARATOR) .
            DIRECTORY_SEPARATOR . $this->resolveFileSubPath($documentType, basename($filePath))
        );

        return $this->allowRenameFiles
            ? dirname($destFilePath) . DIRECTORY_SEPARATOR . Uploader::getNewFileName($destFilePath)
            : $destFilePath;
    }
}
