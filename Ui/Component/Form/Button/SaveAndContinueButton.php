<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Ui\Component\Form\Button;

use Magento\Framework\Phrase;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

final class SaveAndContinueButton implements ButtonProviderInterface
{
    public function getButtonData(): array
    {
        return [
            'label' => new Phrase('Save And Continue'),
            'class' => 'save',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'saveAndContinueEdit']]
            ],
            'sort_order' => 90,
        ];
    }
}
