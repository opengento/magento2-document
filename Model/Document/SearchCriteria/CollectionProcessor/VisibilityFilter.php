<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Document\SearchCriteria\CollectionProcessor;

use Magento\Framework\Api\Filter;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor\FilterProcessor\CustomFilterInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Opengento\Document\Model\ResourceModel\Document\Collection;

final class VisibilityFilter implements CustomFilterInterface
{
    public function apply(Filter $filter, AbstractDb $collection): bool
    {
        /** @var Collection $collection */
        $collection->addVisibilityFilter([$filter->getConditionType() => $filter->getValue()]);

        return true;
    }
}
