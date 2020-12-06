<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Ui\DataProvider\Document\Form\Modifier;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filesystem;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Opengento\Document\Api\DocumentTypeRepositoryInterface;
use Opengento\Document\Model\Document\Helper\File as FileHelper;
use Opengento\Document\Model\Document\RegistryInterface;
use Opengento\Document\Model\File\Info as FileInfo;
use Opengento\Document\Model\File\Url as UrlHelper;
use function basename;

final class File implements ModifierInterface
{
    /**
     * @var RegistryInterface
     */
    private $registry;

    /**
     * @var DocumentTypeRepositoryInterface
     */
    private $docTypeRepository;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var FileInfo
     */
    private $fileInfo;

    /**
     * @var FileHelper
     */
    private $fileHelper;

    /**
     * @var UrlHelper
     */
    private $urlHelper;

    public function __construct(
        RegistryInterface $registry,
        DocumentTypeRepositoryInterface $docTypeRepository,
        Filesystem $filesystem,
        FileInfo $fileInfo,
        FileHelper $fileHelper,
        UrlHelper $urlHelper
    ) {
        $this->registry = $registry;
        $this->docTypeRepository = $docTypeRepository;
        $this->filesystem = $filesystem;
        $this->fileInfo = $fileInfo;
        $this->fileHelper = $fileHelper;
        $this->urlHelper = $urlHelper;
    }

    /**
     * @inheritdoc
     * @throws NoSuchEntityException
     */
    public function modifyData(array $data): array
    {
        foreach ($data as &$document) {
            $document['file_uploader'] = $this->resolveFileUploader($document);
            $document['image_uploader'] = $this->resolveImageUploader($document);
        }

        return $data;
    }

    public function modifyMeta(array $meta): array
    {
        return $meta;
    }

    private function resolveFileUploader(array $document): array
    {
        $fileData = [];

        if (isset($document['file_name'])) {
            $filePath = $this->fileHelper->getFilePath($this->registry->get());
            $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
            if ($mediaDirectory->isExist($filePath)) {
                $fileData[0]['name'] = $document['file_name'];
                $fileData[0]['url'] = $this->urlHelper->getUrl($document['file_path'] . '/' . $document['file_name']);
                $fileData[0]['size'] = $this->fileInfo->stat($filePath)['size'] ?? 0;
                $fileData[0]['type'] = $this->fileInfo->getMimeType($filePath);
            }
        }

        return $fileData;
    }

    /**
     * @param array $document
     * @return array
     * @throws NoSuchEntityException
     */
    private function resolveImageUploader(array $document): array
    {
        $imageData = [];

        if (!isset($document['image_file_name'])) {
            $documentType = $this->docTypeRepository->getById((int) $document['type_id']);
            $document['image_file_name'] = $documentType->getDefaultImageFileName();
        }
        $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $imagePath = $mediaDirectory->getAbsolutePath($document['image_file_name']);

        if ($mediaDirectory->isExist($imagePath)) {
            $imageData[0]['name'] = basename($document['image_file_name']);
            $imageData[0]['url'] = $this->urlHelper->getUrl($document['image_file_name']);
            $imageData[0]['size'] = $this->fileInfo->stat($imagePath)['size'] ?? 0;
            $imageData[0]['type'] = $this->fileInfo->getMimeType($imagePath);
        }

        return $imageData;
    }
}
