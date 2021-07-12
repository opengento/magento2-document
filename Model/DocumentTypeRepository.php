<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model;

use Exception;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Phrase;
use Opengento\Document\Api\Data\DocumentTypeInterface;
use Opengento\Document\Api\Data\DocumentTypeInterfaceFactory;
use Opengento\Document\Api\Data\DocumentTypeSearchResultsInterface;
use Opengento\Document\Api\Data\DocumentTypeSearchResultsInterfaceFactory;
use Opengento\Document\Api\DocumentTypeRepositoryInterface;
use Opengento\Document\Model\Document\Filesystem\File;
use Opengento\Document\Model\ResourceModel\DocumentType as DocumentTypeDb;
use Opengento\Document\Model\ResourceModel\DocumentType\Collection;
use Opengento\Document\Model\ResourceModel\DocumentType\CollectionFactory;

final class DocumentTypeRepository implements DocumentTypeRepositoryInterface
{
    /**
     * @var DocumentTypeInterfaceFactory
     */
    private $documentTypeFactory;

    /**
     * @var DocumentTypeDb
     */
    private $documentTypeDb;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var JoinProcessorInterface
     */
    private $joinProcessor;

    /**
     * @var CollectionProcessor
     */
    private $collectionProcessor;

    /**
     * @var DocumentTypeSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var File
     */
    private $fileHelper;

    /**
     * @var DocumentTypeInterface[]
     */
    private $instances = ['code' => [], 'id' => []];

    public function __construct(
        DocumentTypeInterfaceFactory $documentTypeFactory,
        DocumentTypeDb $documentTypeDb,
        CollectionFactory $collectionFactory,
        JoinProcessorInterface $joinProcessor,
        CollectionProcessor $collectionProcessor,
        DocumentTypeSearchResultsInterfaceFactory $searchResultsFactory,
        File $fileHelper
    ) {
        $this->documentTypeFactory = $documentTypeFactory;
        $this->documentTypeDb = $documentTypeDb;
        $this->collectionFactory = $collectionFactory;
        $this->joinProcessor = $joinProcessor;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->fileHelper = $fileHelper;
    }

    public function getById(int $documentTypeId): DocumentTypeInterface
    {
        if (!isset($this->instances['id'][$documentTypeId])) {
            /** @var DocumentTypeInterface|DocumentType $documentType */
            $documentType = $this->documentTypeFactory->create();
            $this->documentTypeDb->load($documentType, $documentTypeId);
            if (!$documentType->getId()) {
                throw new NoSuchEntityException(new Phrase('No such entity exists with id "%1".', [$documentTypeId]));
            }
            $this->instances['id'][$documentTypeId] = $documentType;
            $this->instances['code'][$documentType->getCode()] = $documentType;
        }

        return $this->instances['id'][$documentTypeId];
    }

    public function getByCode(string $documentTypeCode): DocumentTypeInterface
    {
        if (!isset($this->instances['code'][$documentTypeCode])) {
            /** @var DocumentTypeInterface|DocumentType $documentType */
            $documentType = $this->documentTypeFactory->create();
            $this->documentTypeDb->load($documentType, $documentTypeCode, 'code');
            if (!$documentType->getId()) {
                throw new NoSuchEntityException(
                    new Phrase('No such entity exists with code "%1".', [$documentTypeCode])
                );
            }
            $this->instances['id'][$documentType->getId()] = $documentType;
            $this->instances['code'][$documentTypeCode] = $documentType;
        }

        return $this->instances['code'][$documentTypeCode];
    }

    public function save(DocumentTypeInterface $documentType): DocumentTypeInterface
    {
        try {
            /** @var DocumentType $documentType */
            $this->documentTypeDb->save($documentType);
        } catch (Exception $e) {
            throw new CouldNotSaveException(
                new Phrase('Could not save entity with id "%1".', [$documentType->getId()]),
                $e
            );
        }

        $this->instances['id'][$documentType->getId()] = $documentType;
        $this->instances['code'][$documentType->getCode()] = $documentType;

        return $documentType;
    }

    public function delete(DocumentTypeInterface $documentType): void
    {
        try {
            /** @var DocumentType $documentType */
            $this->documentTypeDb->delete($documentType);
            $this->fileHelper->deleteFile($documentType->getDefaultImageFileName());
        } catch (Exception $e) {
            throw new CouldNotDeleteException(
                new Phrase('Could not save entity with ID "%1".', [$documentType->getId()]), $e
            );
        }

        unset($this->instances['id'][$documentType->getId()], $this->instances['code'][$documentType->getCode()]);
    }

    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $this->joinProcessor->process($collection);
        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var DocumentTypeSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
