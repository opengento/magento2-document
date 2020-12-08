<?php
/**
 * Copyright Â© MP Biomedicals, LLC. All rights reserved.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Document\Collection;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Data\CollectionModifierInterface;
use Opengento\Document\Model\ResourceModel\Document\Collection;

/**
 * @api
 */
final class SelectModifier implements CollectionModifierInterface
{
    public function apply(AbstractDb $collection): void
    {
        /** @var Collection $collection */
        $collection->addFieldToSelect([
            'type_id',
            'name',
            'code',
            'file_name',
            'file_path',
            'image_file_name',
            'description',
            'file_locale',
        ]);
        $collection->join(
            ['odt' => 'opengento_document_type'],
            'main_table.type_id=odt.entity_id',
            [
                'type_code' => 'code',
                'type_name' => 'name',
            ]
        );
        $collection->addDefaultImage();
    }
}
