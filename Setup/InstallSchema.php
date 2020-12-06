<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Zend_Db_Exception;

final class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context): void
    {
        $setup->startSetup();

        $this->installDocumentTypeTable($setup);
        $this->installDocumentTable($setup);

        $setup->endSetup();
    }

    /**
     * @param SchemaSetupInterface $setup
     * @return void
     * @throws Zend_Db_Exception
     */
    private function installDocumentTypeTable(SchemaSetupInterface $setup): void
    {
        $connection = $setup->getConnection();
        $tableName = $connection->getTableName('opengento_document_type');
        $newTable = $connection->newTable($tableName)
            ->addColumn(
                'entity_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true],
                'Document Type Entity ID'
            )->addColumn(
                'code',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Document Type Identifier'
            )->addColumn(
                'scheduled_import',
                Table::TYPE_BOOLEAN,
                null,
                ['nullable' => false],
                'Document Type Schedule Import'
            )->addColumn(
                'name',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Document Type Title'
            )->addColumn(
                'file_source_path',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Files Source Path of the Document Type'
            )->addColumn(
                'file_dest_path',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Files Destination Path of the Document Type'
            )->addColumn(
                'sub_path_length',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false],
                'Destination Sub Path Length of the Document Type'
            )->addColumn(
                'file_pattern',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Document File Pattern'
            )->addColumn(
                'file_allowed_extensions',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Document Allowed Extensions'
            )->addColumn(
                'default_image_file_name',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Document Default Image File Name'
            )->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT]
            )->addColumn(
                'updated_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => true, 'default' => Table::TIMESTAMP_INIT_UPDATE]
            )->addIndex(
                $setup->getIdxName($tableName, ['scheduled_import'], AdapterInterface::INDEX_TYPE_INDEX),
                ['scheduled_import'],
                ['type' => AdapterInterface::INDEX_TYPE_INDEX]
            )->addIndex(
                $setup->getIdxName($tableName, ['code'], AdapterInterface::INDEX_TYPE_UNIQUE),
                ['code'],
                ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
            );
        $connection->createTable($newTable);
    }

    /**
     * @param SchemaSetupInterface $setup
     * @return void
     * @throws Zend_Db_Exception
     */
    private function installDocumentTable(SchemaSetupInterface $setup): void
    {
        $connection = $setup->getConnection();
        $tableName = $connection->getTableName('opengento_document');
        $newTable = $connection->newTable($tableName)
            ->addColumn(
                'entity_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true],
                'Document Entity ID'
            )->addColumn(
                'type_id',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' => true],
                'Document related Type ID'
            )->addColumn(
                'code',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Document Identifier'
            )->addColumn(
                'name',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Document Name'
            )->addColumn(
                'description',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Document Description'
            )->addColumn(
                'file_locale',
                Table::TYPE_TEXT,
                8,
                ['nullable' => true],
                'Document Locale'
            )->addColumn(
                'file_name',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Document File Name'
            )->addColumn(
                'file_path',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Document File Relative Path'
            )->addColumn(
                'image_file_name',
                Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Document Image File Name'
            )->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT]
            )->addColumn(
                'updated_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => true, 'default' => Table::TIMESTAMP_INIT_UPDATE]
            )->addIndex(
                $setup->getIdxName($tableName, ['code', 'type_id'], AdapterInterface::INDEX_TYPE_UNIQUE),
                ['code', 'type_id'],
                ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
            )->addForeignKey(
                $setup->getFkName(
                    'opengento_document',
                    'type_id',
                    'opengento_document_type',
                    'entity_id'
                ),
                'type_id',
                $setup->getTable('opengento_document_type'),
                'entity_id',
                Table::ACTION_CASCADE
            );
        $connection->createTable($newTable);
    }
}
