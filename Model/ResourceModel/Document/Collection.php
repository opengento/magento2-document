<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\ResourceModel\Document;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Opengento\Document\Api\Data\DocumentInterface;
use Opengento\Document\Model\Document;
use Opengento\Document\Model\ResourceModel\Document as DocumentDb;
use function in_array;

/**
 * @method DocumentInterface[] getItems()
 */
class Collection extends AbstractCollection
{
    protected function _construct(): void
    {
        $this->_init(Document::class, DocumentDb::class);
    }

    public function addDefaultImage(string $alias = 'default_image_file_name'): Collection
    {
        if ($this->_fieldsToSelect && in_array($alias, $this->_fieldsToSelect, true)) {
            $this->removeFieldFromSelect($alias);
        }
        $this->join(['mdt' => 'opengento_document_type'], 'main_table.type_id=mdt.entity_id', '');
        $this->getSelect()->columns([$alias => 'mdt.default_image_file_name']);

        return $this;
    }
}
