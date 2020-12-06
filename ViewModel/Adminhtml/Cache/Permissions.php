<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\ViewModel\Adminhtml\Cache;

use Magento\Framework\AuthorizationInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

final class Permissions implements ArgumentInterface
{
    /**
     * @var AuthorizationInterface
     */
    private $authorization;

    public function __construct(
        AuthorizationInterface $authorization
    ) {
        $this->authorization = $authorization;
    }

    public function hasAccessToFlushDocumentImages(): bool
    {
        return $this->authorization->isAllowed('Opengento_Document::flush_images');
    }
}
