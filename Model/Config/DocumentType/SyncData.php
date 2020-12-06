<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Config\DocumentType;

use Magento\Framework\Config\DataInterface;
use Magento\Framework\EntityManager\HydratorPool;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Opengento\Document\Api\Data\DocumentTypeInterface;
use Opengento\Document\Api\Data\DocumentTypeInterfaceFactory;
use Opengento\Document\Api\DocumentTypeRepositoryInterface;

final class SyncData
{
    /**
     * @var DataInterface
     */
    private $configData;

    /**
     * @var DocumentTypeInterfaceFactory
     */
    private $docTypeFactory;

    /**
     * @var DocumentTypeRepositoryInterface
     */
    private $docTypeRepository;

    /**
     * @var HydratorPool
     */
    private $hydratorPool;

    public function __construct(
        DataInterface $configData,
        DocumentTypeInterfaceFactory $docTypeFactory,
        DocumentTypeRepositoryInterface $docTypeRepository,
        HydratorPool $hydratorPool
    ) {
        $this->configData = $configData;
        $this->docTypeFactory = $docTypeFactory;
        $this->docTypeRepository = $docTypeRepository;
        $this->hydratorPool = $hydratorPool;
    }

    /**
     * @throws CouldNotSaveException
     */
    public function execute(): void
    {
        $hydrator = $this->hydratorPool->getHydrator(DocumentTypeInterface::class);

        foreach ($this->configData->get(null) as $key => $data) {
            try {
                $documentType = $this->docTypeRepository->getByCode($key);
            } catch (NoSuchEntityException $e) {
                $documentType = $this->docTypeFactory->create();
            }

            /** @var DocumentTypeInterface $documentType */
            $documentType = $hydrator->hydrate($documentType, $data);
            $this->docTypeRepository->save($documentType);
        }
    }
}
