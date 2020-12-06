<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Ui\Component\DocumentType\Form\Button;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Opengento\Document\Model\DocumentType\AuthorizationInterface;
use Opengento\Document\Model\DocumentType\RegistryInterface;
use Opengento\Document\Ui\Component\Form\Button\SaveButton as DefaultSaveButton;

final class SaveButton implements ButtonProviderInterface
{
    /**
     * @var DefaultSaveButton
     */
    private $saveButton;

    /**
     * @var RegistryInterface
     */
    private $registry;

    /**
     * @var AuthorizationInterface
     */
    private $authorization;

    public function __construct(
        DefaultSaveButton $saveButton,
        RegistryInterface $registry,
        AuthorizationInterface $authorization
    ) {
        $this->saveButton = $saveButton;
        $this->registry = $registry;
        $this->authorization = $authorization;
    }

    public function getButtonData(): array
    {
        return $this->authorization->isReadonly($this->registry->get()->getCode()) ? [] : $this->saveButton->getButtonData();
    }
}
