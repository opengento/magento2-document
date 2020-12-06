<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\File;

use Exception;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Phrase;
use Opengento\Document\Exception\UploadException;
use Opengento\Document\Model\Document\Helper\File;

final class UploaderHandler
{
    /**
     * @var UploaderFactory
     */
    private $uploaderFactory;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var Url
     */
    private $urlHelper;

    /**
     * @var string[]
     */
    private $imgAllowedExtensions;

    public function __construct(
        UploaderFactory $uploaderFactory,
        Filesystem $filesystem,
        Url $urlHelper,
        array $imgAllowedExtensions = []
    ) {
        $this->uploaderFactory = $uploaderFactory;
        $this->filesystem = $filesystem;
        $this->urlHelper = $urlHelper;
        $this->imgAllowedExtensions = $imgAllowedExtensions;
    }

    /**
     * @param string $fileId
     * @param  string[] $fileExtensions
     * @return string[]
     * @throws UploadException
     */
    public function upload(string $fileId, array $fileExtensions = []): array
    {
        /** @var Uploader $uploader */
        $uploader = $this->uploaderFactory->create(['fileId' => $fileId]);

        $uploader->setAllowCreateFolders(true);
        $uploader->setAllowedExtensions($fileExtensions);
        $uploader->setAllowRenameFiles(true);
        $uploader->setFilenamesCaseSensitivity(true);
        $uploader->setFilesDispersion(false);

        try {
            $upload = (array) $uploader->save(
                $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath(File::TMP_PATH)
            );
        } catch (Exception $e) {
            throw new UploadException(new Phrase($e->getMessage()));
        }

        if (!$upload) {
            throw new UploadException(new Phrase('An error occurred on the file upload.'));
        }

        $upload['url'] = $this->urlHelper->getUrl(File::TMP_PATH . $upload['file']);

        return $upload;
    }

    public function getImageAllowedExtensions(): array
    {
        return $this->imgAllowedExtensions;
    }
}
