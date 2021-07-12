<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Document\Processor;

use Opengento\Document\Api\Data\DocumentInterface;
use Opengento\Document\Api\Data\DocumentTypeInterface;
use Opengento\Document\Model\Document\Filesystem\File;
use Opengento\Document\Model\Document\Filesystem\Format;
use Opengento\Document\Model\Document\ProcessorInterface;
use Opengento\Document\Model\DocumentBuilder;
use function basename;
use function dirname;

final class SimpleProcessor implements ProcessorInterface
{
    public const CODE = 'simple';

    /**
     * @var DocumentBuilder
     */
    private $documentBuilder;

    /**
     * @var File
     */
    private $fileHelper;

    public function __construct(
        DocumentBuilder $documentBuilder,
        File $fileHelper
    ) {
        $this->documentBuilder = $documentBuilder;
        $this->fileHelper = $fileHelper;
    }

    public function execute(DocumentTypeInterface $documentType, string $filePath): DocumentInterface
    {
        $destFilePath = $this->fileHelper->getFileDestPath($documentType, $filePath);
        $fileName = basename($destFilePath);

        $this->documentBuilder->setTypeId($documentType->getId());
        $this->documentBuilder->setCode(Format::formatCode($fileName));
        $this->documentBuilder->setName(Format::formatName($fileName));
        $this->documentBuilder->setFileName($fileName);
        $this->documentBuilder->setFilePath(dirname($this->fileHelper->getRelativeFilePath($destFilePath)));

        return $this->documentBuilder->create();
    }
}
