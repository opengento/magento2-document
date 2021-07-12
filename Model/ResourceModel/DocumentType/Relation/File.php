<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\ResourceModel\DocumentType\Relation;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\VersionControl\RelationInterface;
use Opengento\Document\Api\Data\DocumentTypeInterface;
use Opengento\Document\Model\Document\Filesystem\File as FileHelper;

final class File implements RelationInterface
{
    /**
     * @var FileHelper
     */
    private $fileHelper;

    public function __construct(
        FileHelper $fileHelper
    ) {
        $this->fileHelper = $fileHelper;
    }

    public function processRelation(AbstractModel $object): void
    {
        if ($object instanceof DocumentTypeInterface &&
            $object->dataHasChangedFor('default_image_file_name') && $object->getOrigData('default_image_file_name')
        ) {
            $this->fileHelper->deleteFile($object->getOrigData('default_image_file_name'));
        }
    }
}
