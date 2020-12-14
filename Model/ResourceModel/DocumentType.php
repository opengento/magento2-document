<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Model\ResourceModel\Db\VersionControl\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\VersionControl\RelationComposite;
use Magento\Framework\Model\ResourceModel\Db\VersionControl\Snapshot;
use Magento\Framework\Validator\ValidatorInterface;

class DocumentType extends AbstractDb
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(
        Context $context,
        Snapshot $entitySnapshot,
        RelationComposite $entityRelationComposite,
        ValidatorInterface $validator,
        ?string $connectionName = null
    ) {
        $this->validator = $validator;
        parent::__construct($context, $entitySnapshot, $entityRelationComposite, $connectionName);
    }

    public function getValidationRulesBeforeSave(): ValidatorInterface
    {
        return $this->validator;
    }

    protected function _construct(): void
    {
        $this->_init('opengento_document_type', 'entity_id');
        $this->addUniqueField(['field' => 'code', 'title' => 'Document type with same code']);
    }
}
