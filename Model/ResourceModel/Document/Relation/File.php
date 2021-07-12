<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\ResourceModel\Document\Relation;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\VersionControl\RelationInterface;
use Opengento\Document\Api\Data\DocumentInterface;
use Opengento\Document\Model\Document\Filesystem\File as FileHelper;
use const DIRECTORY_SEPARATOR;

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
        if ($object instanceof DocumentInterface) {
            if ($object->getOrigData('file_path') && $object->getOrigData('file_name') &&
                ($object->dataHasChangedFor('file_path') || $object->dataHasChangedFor('file_name'))
            ) {
                $this->fileHelper->deleteFile(
                    $object->getOrigData('file_path') . DIRECTORY_SEPARATOR . $object->getOrigData('file_name')
                );
            }
            if ($object->dataHasChangedFor('image_file_name') && $object->getOrigData('image_file_name')) {
                $this->fileHelper->deleteFile($object->getOrigData('image_file_name'));
            }
        }
    }
}
