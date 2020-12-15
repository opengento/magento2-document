<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Ui\DataProvider\DocumentImport\Listing;

use Magento\Framework\Api\Filter;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Opengento\Document\Model\ResourceModel\DocumentType\CollectionFactory;
use function array_reduce;

final class DataProvider extends AbstractDataProvider
{
    /**
     * @var PoolInterface
     */
    private $pool;

    /**
     * @var array|null
     */
    private $loadedData;

    /**
     * @var array|null
     */
    private $loadedMeta;

    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $collectionFactory,
        PoolInterface $pool,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->pool = $pool;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function addField($field, $alias = null): void
    {
        if ($field === 'total_files' || $alias === 'total_files') {
            $this->collection->addFilesCount($alias ?? $field);
        } else {
            parent::addField($field, $alias);
        }
    }

    public function addFilter(Filter $filter): void
    {
        if ($filter->getField() === 'total_files') {
            $this->collection->addFilesCountFilter(
                $filter->getField(),
                [$filter->getConditionType() => $filter->getValue()]
            );
        } else {
            parent::addFilter($filter);
        }
    }

    /**
     * @throws LocalizedException
     */
    public function getData(): array
    {
        return $this->loadedData ?? $this->loadedData = array_reduce(
            $this->pool->getModifiersInstances(),
            static function (array $data, ModifierInterface $modifier): array { return $modifier->modifyData($data); },
            parent::getData()
        );
    }

    /**
     * @throws LocalizedException
     */
    public function getMeta(): array
    {
        return $this->loadedMeta ?? $this->loadedMeta = array_reduce(
            $this->pool->getModifiersInstances(),
            static function (array $meta, ModifierInterface $modifier): array { return $modifier->modifyMeta($meta); },
            parent::getMeta()
        );
    }
}
