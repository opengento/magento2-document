<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Controller\Adminhtml\Index\Save;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\EntityManager\HydratorPool;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\ValidatorException;
use Opengento\Document\Api\Data\DocumentInterface;
use Opengento\Document\Api\Data\DocumentTypeInterface;
use Opengento\Document\Api\DocumentTypeRepositoryInterface;
use Opengento\Document\Model\Document\Helper\File;
use Opengento\Document\Model\File\Validator;
use function array_merge;
use function basename;
use function dirname;

final class FileHandler implements HandlerInterface
{
    /**
     * @var DocumentTypeRepositoryInterface
     */
    private $docTypeRepository;

    /**
     * @var HydratorPool
     */
    private $hydratorPool;

    /**
     * @var File
     */
    private $fileHelper;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var array
     */
    private $rollbackData;

    public function __construct(
        DocumentTypeRepositoryInterface $docTypeRepository,
        HydratorPool $hydratorPool,
        File $fileHelper,
        Validator $validator
    ) {
        $this->docTypeRepository = $docTypeRepository;
        $this->hydratorPool = $hydratorPool;
        $this->fileHelper = $fileHelper;
        $this->validator = $validator;
    }

    public function execute(RequestInterface $request, DocumentInterface $document): DocumentInterface
    {
        $this->rollbackData = [];
        $documentType = $this->docTypeRepository->getById(
            (int) ($document->getTypeId() ?: $request->getParam('type_id'))
        );

        $data = array_merge($this->processFile($documentType, $request), $this->processImage($documentType, $request));

        return $this->hydratorPool->getHydrator(DocumentInterface::class)->hydrate($document, $data);
    }

    public function rollback(RequestInterface $request): void
    {
        if (isset($this->rollbackData['destFilePath'])) {
            $this->fileHelper->deleteFile($this->rollbackData['destFilePath']);
        }
        if (isset($this->rollbackData['destImagePath'])) {
            $this->fileHelper->deleteFile($this->rollbackData['destImagePath']);
        }
        $this->rollbackData = [];
    }

    /**
     * @param DocumentTypeInterface $documentType
     * @param RequestInterface $request
     * @return string[]
     * @throws FileSystemException
     * @throws ValidatorException
     */
    private function processFile(DocumentTypeInterface $documentType, RequestInterface $request): array
    {
        $data = [];
        $fileUploader = $request->getParam('file_uploader');

        if (!$fileUploader) {
            $data['file_name'] = null;
            $data['file_path'] = null;
        } elseif (isset($fileUploader[0]['path'], $fileUploader[0]['file'])) {
            $fileSrcPath = $fileUploader[0]['path'] . $fileUploader[0]['file'];

            $this->validator->validate($fileSrcPath, $documentType->getFileAllowedExtensions());

            $destFilePath = $this->fileHelper->getFileDestPath($documentType, $fileSrcPath);
            $this->rollbackData['destFilePath'] = $destFilePath;
            $this->fileHelper->moveFile($fileSrcPath, $destFilePath);
            $data['file_name'] = basename($destFilePath);
            $data['file_path'] = dirname($this->fileHelper->getRelativeFilePath($destFilePath));
        }

        return $data;
    }

    /**
     * @param DocumentTypeInterface $documentType
     * @param RequestInterface $request
     * @return string[]
     * @throws FileSystemException
     */
    private function processImage(DocumentTypeInterface $documentType, RequestInterface $request): array
    {
        $data = [];
        $imageUploader = $request->getParam('image_uploader');

        if (!$imageUploader) {
            $data['image_file_name'] = null;
        } elseif (isset($imageUploader[0]['path'], $imageUploader[0]['file'])) {
            $imageSrcPath = $imageUploader[0]['path'] . $imageUploader[0]['file'];

            $destImagePath = $this->fileHelper->getImageDestPath($documentType, $imageSrcPath);
            $this->rollbackData['destImagePath'] = $destImagePath;
            $this->fileHelper->moveFile($imageSrcPath, $destImagePath);
            $data['image_file_name'] = $this->fileHelper->getRelativeFilePath($destImagePath);
        }

        return $data;
    }
}
