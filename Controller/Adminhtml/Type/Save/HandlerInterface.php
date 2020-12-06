<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Controller\Adminhtml\Type\Save;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Opengento\Document\Api\Data\DocumentTypeInterface;

/**
 * @api
 */
interface HandlerInterface
{
    /**
     * @inheritdoc
     * @throws CouldNotSaveException
     * @throws LocalizedException
     */
    public function execute(RequestInterface $request, DocumentTypeInterface $documentType): DocumentTypeInterface;

    public function rollback(RequestInterface $request): void;
}
