<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Ui\DataProvider\DocumentType\Form\Modifier;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Opengento\Document\Model\File\Info as FileInfo;
use Opengento\Document\Model\File\Url as UrlHelper;
use function basename;

final class File implements ModifierInterface
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var FileInfo
     */
    private $fileInfo;

    /**
     * @var UrlHelper
     */
    private $urlHelper;

    public function __construct(
        Filesystem $filesystem,
        FileInfo $fileInfo,
        UrlHelper $urlHelper
    ) {
        $this->filesystem = $filesystem;
        $this->fileInfo = $fileInfo;
        $this->urlHelper = $urlHelper;
    }

    public function modifyData(array $data): array
    {
        foreach ($data as &$documentType) {
            $documentType['image_uploader'] = $this->resolveImageUploader($documentType);
        }

        return $data;
    }

    public function modifyMeta(array $meta): array
    {
        return $meta;
    }

    private function resolveImageUploader(array $documentType): array
    {
        $imageData = [];

        if (isset($documentType['default_image_file_name'])) {
            $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
            $imagePath = $mediaDirectory->getAbsolutePath($documentType['default_image_file_name']);

            if ($mediaDirectory->isExist($imagePath)) {
                $imageData[] = [
                    'name' => basename($documentType['default_image_file_name']),
                    'url' => $this->urlHelper->getUrl($documentType['default_image_file_name']),
                    'size' => $this->fileInfo->stat($imagePath)['size'] ?? 0,
                    'type' => $this->fileInfo->getMimeType($imagePath),
                ];
            }
        }

        return $imageData;
    }
}
