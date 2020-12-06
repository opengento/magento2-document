<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Ui\DataProvider\Document\Form;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Opengento\Document\Model\ResourceModel\Document\CollectionFactory;
use function array_merge;
use function array_reduce;

final class DataProvider extends AbstractDataProvider
{
    /**
     * @var PoolInterface
     */
    private $pool;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

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
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->pool = $pool;
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @throws LocalizedException
     */
    public function getData(): array
    {
        return $this->loadedData ?? $this->loadedData = array_reduce(
            $this->pool->getModifiersInstances(),
            static function (array $data, ModifierInterface $modifier): array { return $modifier->modifyData($data); },
            $this->resolveDataSource(parent::getData()['items'] ?? [])
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

    private function resolveDataSource(array $dataSource): array
    {
        $documentData = [];

        foreach ($dataSource as $data) {
            $documentData[(int) ($data[$this->getPrimaryFieldName()] ?? 0)] = $data;
        }
        if ($this->dataPersistor->get('document_post_data')) {
            $postData = $this->dataPersistor->get('document_post_data') ?? [];
            $postDataEntityId = (int) ($postData[$this->getPrimaryFieldName()] ?? 0);
            $documentData[$postDataEntityId] = array_merge($documentData[$postDataEntityId] ?? [], $postData);

            $this->dataPersistor->clear('document_post_data');
        }

        return $documentData;
    }
}
