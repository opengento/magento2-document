<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Setup;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Opengento\Document\Model\Config\DocumentType\SyncData;

final class Recurring implements InstallSchemaInterface
{
    /**
     * @var SyncData
     */
    private $syncData;

    public function __construct(
        SyncData $syncData
    ) {
        $this->syncData = $syncData;
    }

    /**
     * @inheritdoc
     * @throws CouldNotSaveException
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context): void
    {
        $this->syncData->execute();
    }
}
