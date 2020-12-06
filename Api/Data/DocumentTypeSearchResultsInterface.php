<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * @api
 */
interface DocumentTypeSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @inheritdoc
     * @return \Opengento\Document\Api\Data\DocumentTypeInterface[]
     */
    public function getItems(): array;

    /**
     * @inheritdoc
     * @param \Opengento\Document\Api\Data\DocumentTypeInterface[] $items
     * @return $this
     */
    public function setItems(array $items): self;
}
