<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Document\Operation;

use Magento\Framework\Exception\CouldNotSaveException;
use Opengento\Document\Api\Data\DocumentInterface;
use Opengento\Document\Api\Data\DocumentTypeInterface;
use Opengento\Document\Api\DocumentRepositoryInterface;
use Opengento\Document\Model\Document\Filesystem\File;
use Opengento\Document\Model\Document\ProcessorFactory;

final class CreateFromFile implements CreateFromFileInterface
{
    /**
     * @var ProcessorFactory
     */
    private $processorFactory;

    /**
     * @var DocumentRepositoryInterface
     */
    private $documentRepository;

    /**
     * @var File
     */
    private $fileHelper;

    public function __construct(
        ProcessorFactory $processorFactory,
        DocumentRepositoryInterface $documentRepository,
        File $fileHelper
    ) {
        $this->processorFactory = $processorFactory;
        $this->documentRepository = $documentRepository;
        $this->fileHelper = $fileHelper;
    }

    public function execute(DocumentTypeInterface $documentType, string $file): DocumentInterface
    {
        $document = $this->processorFactory->get($documentType->getCode())->execute($documentType, $file);
        $destFilePath = $this->fileHelper->getFilePath($document);
        $this->fileHelper->moveFile($file, $destFilePath);

        try {
            return $this->documentRepository->save($document);
        } catch (CouldNotSaveException $e) {
            $this->fileHelper->moveFile($destFilePath, $file);
            throw $e->getPrevious() ?? $e;
        }
    }
}
