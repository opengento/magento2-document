<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Ui\Component\DocumentType\Form\Button;

use Magento\Framework\Phrase;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Opengento\Document\Model\DocumentType\AuthorizationInterface;
use Opengento\Document\Model\DocumentType\RegistryInterface;
use function sprintf;

final class DeleteButton implements ButtonProviderInterface
{
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var RegistryInterface
     */
    private $registry;

    /**
     * @var AuthorizationInterface
     */
    private $authorization;

    public function __construct(
        UrlInterface $urlBuilder,
        RegistryInterface $registry,
        AuthorizationInterface $authorization
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->registry = $registry;
        $this->authorization = $authorization;
    }

    public function getButtonData(): array
    {
        $data = [];
        $documentType = $this->registry->get();

        if ($documentType->getId() && !$this->authorization->isReadonly($documentType->getCode())) {
            $data = [
                'label' => new Phrase('Delete'),
                'class' => 'delete',
                'on_click' => sprintf(
                    'deleteConfirm(\'%s\', \'%s\', {"data": {}})',
                    new Phrase('Are you sure you want to do this?'),
                    $this->urlBuilder->getUrl('*/*/delete', ['id' => $documentType->getId()])
                ),
                'sort_order' => 20,
            ];
        }

        return $data;
    }
}
