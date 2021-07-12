<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Opengento\Document\Model\ResourceModel\DocumentType\Collection;
use Opengento\Document\Model\ResourceModel\DocumentType\CollectionFactory;

final class DocumentTypes implements OptionSourceInterface
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var string[][]|null
     */
    private $options;

    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    public function toOptionArray(): array
    {
        if (!$this->options) {
            /** @var Collection $collection */
            $collection = $this->collectionFactory->create();
            $collection->addFieldToSelect(['id' => 'entity_id', 'name' => 'name']);
            $this->options = $collection->toOptionArray();
        }

        return $this->options;
    }
}
