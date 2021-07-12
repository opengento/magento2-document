<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Controller\Adminhtml\Type\Save;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\EntityManager\HydratorPool;
use Opengento\Document\Api\Data\DocumentTypeInterface;
use Opengento\Document\Model\Document\Filesystem\File;

final class FileHandler implements HandlerInterface
{
    /**
     * @var HydratorPool
     */
    private $hydratorPool;

    /**
     * @var File
     */
    private $fileHelper;

    /**
     * @var array
     */
    private $rollbackData;

    public function __construct(
        HydratorPool $hydratorPool,
        File $fileHelper
    ) {
        $this->hydratorPool = $hydratorPool;
        $this->fileHelper = $fileHelper;
    }

    public function execute(RequestInterface $request, DocumentTypeInterface $documentType): DocumentTypeInterface
    {
        $data = [];
        $this->rollbackData = [];
        $imageUploader = $request->getParam('image_uploader');

        if (!$imageUploader) {
            $data['default_image_file_name'] = null;
        } elseif (isset($imageUploader[0]['path'], $imageUploader[0]['file'])) {
            $imageSrcPath = $imageUploader[0]['path'] . $imageUploader[0]['file'];
            $destImagePath = $this->fileHelper->getImageDestPath($documentType, $imageSrcPath);
            $this->rollbackData['destImagePath'] = $destImagePath;
            $this->fileHelper->moveFile($imageSrcPath, $destImagePath);
            $data['default_image_file_name'] = $this->fileHelper->getRelativeFilePath($destImagePath);
        }
        if ($data) {
            $documentType = $this->hydratorPool->getHydrator(DocumentTypeInterface::class)->hydrate($documentType, $data);
        }

        return $documentType;
    }

    public function rollback(RequestInterface $request): void
    {
        if (isset($this->rollbackData['destImagePath'])) {
            $this->fileHelper->deleteFile($this->rollbackData['destImagePath']);
        }
        $this->rollbackData = [];
    }
}
