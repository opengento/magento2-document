<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Config\Source;

use Magento\Framework\Config\DataInterface;
use Magento\Framework\Data\OptionSourceInterface;

final class ReadOnlyDocumentTypes implements OptionSourceInterface
{
    /**
     * @var DataInterface
     */
    private $configData;

    /**
     * @var string[][]|null
     */
    private $options;

    public function __construct(
        DataInterface $configData
    ) {
        $this->configData = $configData;
    }

    public function toOptionArray(): array
    {
        if (!$this->options) {
            $this->options = [];
            foreach ($this->configData->get(null) as $code => $data) {
                $this->options[] = ['value' => $code, 'label' => $data['name']];
            }
        }

        return $this->options;
    }
}
