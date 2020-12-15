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

    public function addFilesCount(string $alias = 'total_files'): self
    {
        $this->getSelect()->joinLeft(
            ['od' => $this->getTable('opengento_document')],
            'main_table.entity_id=od.type_id',
            ''
        );
        $this->addExpressionFieldToSelect($alias, 'COUNT({{file_id}})', ['file_id' => 'od.entity_id']);
        $this->getSelect()->group(['main_table.entity_id']);

        return $this;
    }

    public function addFilesCountFilter(string $alias, array $condition): self
    {
        $this->addFilesCount($alias);
        $this->getSelect()->having($this->_getConditionSql('total_files', $condition));

        return $this;
    }

    public function toOptionArray(): array
    {
        return $this->_toOptionArray('entity_id');
    }
}
