<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Controller\Adminhtml\Index\Save;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Opengento\Document\Api\Data\DocumentInterface;

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
    public function execute(RequestInterface $request, DocumentInterface $document): DocumentInterface;

    public function rollback(RequestInterface $request): void;
}
