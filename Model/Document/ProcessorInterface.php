<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Document;

use Opengento\Document\Api\Data\DocumentInterface;
use Opengento\Document\Api\Data\DocumentTypeInterface;
use Opengento\Document\Exception\InvalidTypeException;

/**
 * @api
 */
interface ProcessorInterface
{
    /**
     * @param DocumentTypeInterface $documentType
     * @param string $filePath
     * @return DocumentInterface
     * @throws InvalidTypeException
     */
    public function execute(DocumentTypeInterface $documentType, string $filePath): DocumentInterface;
}
