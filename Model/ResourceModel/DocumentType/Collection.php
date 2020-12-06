<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\ResourceModel\DocumentType;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Opengento\Document\Api\Data\DocumentTypeInterface;
use Opengento\Document\Model\DocumentType;
use Opengento\Document\Model\ResourceModel\DocumentType as DocumentTypeDb;

/**
 * @method DocumentTypeInterface[] getItems()
 */
class Collection extends AbstractCollection
{
    protected function _construct(): void
    {
        $this->_init(DocumentType::class, DocumentTypeDb::class);
    }

    public function toOptionArray(): array
    {
        return $this->_toOptionArray('entity_id');
    }
}
