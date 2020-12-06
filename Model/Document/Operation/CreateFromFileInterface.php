<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Document\Operation;

use Exception;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Opengento\Document\Api\Data\DocumentInterface;
use Opengento\Document\Api\Data\DocumentTypeInterface;
use Opengento\Document\Exception\InvalidTypeException;

/**
 * @api
 */
interface CreateFromFileInterface
{
    /**
     * @param DocumentTypeInterface $documentType
     * @param string $file
     * @return DocumentInterface
     * @throws InvalidTypeException
     * @throws CouldNotSaveException
     * @throws LocalizedException
     * @throws Exception
     */
    public function execute(DocumentTypeInterface $documentType, string $file): DocumentInterface;
}
