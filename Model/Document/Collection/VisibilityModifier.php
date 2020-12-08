<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Document\Collection;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Data\CollectionModifierInterface;
use Opengento\Document\Model\ResourceModel\Document\Collection;

/**
 * @api
 */
final class VisibilityModifier implements CollectionModifierInterface
{
    /**
     * @var string[]
     */
    private $visibilities;

    public function __construct(
        array $visibilities
    ) {
        $this->visibilities = $visibilities;
    }

    public function apply(AbstractDb $collection): void
    {
        /** @var Collection $collection */
        $collection->addVisibilityFilter(['in' => $this->visibilities]);
    }
}
