<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Ui\Component\Document\Form\Button;

use Magento\Framework\Phrase;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Opengento\Document\Model\Document\RegistryInterface;
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

    public function __construct(
        UrlInterface $urlBuilder,
        RegistryInterface $registry
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->registry = $registry;
    }

    public function getButtonData(): array
    {
        $data = [];
        $entityId = $this->registry->get()->getId();

        if ($entityId) {
            $data = [
                'label' => new Phrase('Delete'),
                'class' => 'delete',
                'on_click' => sprintf(
                    'deleteConfirm(\'%s\', \'%s\')',
                    new Phrase('Are you sure you want to do this?'),
                    $this->urlBuilder->getUrl('*/*/delete', ['id' => $entityId])
                ),
                'sort_order' => 20,
            ];
        }

        return $data;
    }
}
