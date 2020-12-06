<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\VersionControl\AbstractDb;

class DocumentType extends AbstractDb
{
    protected function _construct(): void
    {
        $this->_init('opengento_document_type', 'entity_id');
        $this->addUniqueField(['field' => 'code', 'title' => 'Document type with same code']);
    }
}
