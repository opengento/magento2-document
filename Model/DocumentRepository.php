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
use Opengento\Document\Api\Data\DocumentInterface;
use Opengento\Document\Api\Data\DocumentInterfaceFactory;
use Opengento\Document\Api\Data\DocumentSearchResultsInterface;
use Opengento\Document\Api\Data\DocumentSearchResultsInterfaceFactory;
use Opengento\Document\Api\DocumentRepositoryInterface;
use Opengento\Document\Model\Document\Helper\File;
use Opengento\Document\Model\ResourceModel\Document as DocumentDb;
use Opengento\Document\Model\ResourceModel\Document\Collection;
use Opengento\Document\Model\ResourceModel\Document\CollectionFactory;

final class DocumentRepository implements DocumentRepositoryInterface
{
    /**
     * @var DocumentInterfaceFactory
     */
    private $documentFactory;

    /**
     * @var DocumentDb
     */
    private $documentDb;

    /**
     * @var DocumentSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

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
     * @var File
     */
    private $fileHelper;

    /**
     * @var DocumentInterface[]
     */
    private $instances = ['code' => [], 'id' => []];

    public function __construct(
        DocumentInterfaceFactory $documentFactory,
        DocumentDb $documentDb,
        DocumentSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionFactory $collectionFactory,
        JoinProcessorInterface $joinProcessor,
        CollectionProcessor $collectionProcessor,
        File $fileHelper
    ) {
        $this->documentFactory = $documentFactory;
        $this->documentDb = $documentDb;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionFactory = $collectionFactory;
        $this->joinProcessor = $joinProcessor;
        $this->collectionProcessor = $collectionProcessor;
        $this->fileHelper = $fileHelper;
    }

    public function getById(int $documentId): DocumentInterface
    {
        if (!isset($this->instances['id'][$documentId])) {
            /** @var DocumentInterface|Document $document */
            $document = $this->documentFactory->create();
            $this->documentDb->load($document, $documentId);
            if (!$document->getId()) {
                throw new NoSuchEntityException(new Phrase('No such entity exists with id "%1".', [$documentId]));
            }
            $this->instances['id'][$documentId] = $document;
            $this->instances['code'][$document->getCode()] = $document;
        }

        return $this->instances['id'][$documentId];
    }

    public function getByCode(string $documentCode): DocumentInterface
    {
        if (!isset($this->instances['code'][$documentCode])) {
            /** @var DocumentInterface|Document $document */
            $document = $this->documentFactory->create();
            $this->documentDb->load($document, $documentCode, 'code');
            if (!$document->getCode()) {
                throw new NoSuchEntityException(new Phrase('No such entity exists with code "%1".', [$documentCode]));
            }
            $this->instances['id'][$document->getId()] = $document;
            $this->instances['code'][$documentCode] = $document;
        }

        return $this->instances['code'][$documentCode];
    }

    public function save(DocumentInterface $document): DocumentInterface
    {
        try {
            /** @var Document $document */
            $this->documentDb->save($document);
        } catch (Exception $e) {
            throw new CouldNotSaveException(
                new Phrase('Could not save entity with id "%1".', [$document->getId()]),
                $e
            );
        }

        $this->instances['id'][$document->getId()] = $document;
        $this->instances['code'][$document->getCode()] = $document;

        return $document;
    }

    public function delete(DocumentInterface $document): void
    {
        try {
            /** @var Document $document */
            $this->documentDb->delete($document);
            $this->fileHelper->deleteFile($this->fileHelper->getFilePath($document));
            $this->fileHelper->deleteFile($document->getImageFileName());
        } catch (Exception $e) {
            throw new CouldNotDeleteException(
                new Phrase('Could not save entity with id "%1".', [$document->getId()]),
                $e
            );
        }

        unset($this->instances['id'][$document->getId()], $this->instances['code'][$document->getCode()]);
    }

    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $this->joinProcessor->process($collection);
        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var DocumentSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
