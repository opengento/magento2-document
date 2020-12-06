<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Opengento\Document\Api\Data\DocumentInterface;
use Opengento\Document\Api\Data\DocumentSearchResultsInterface;

/**
 * @api
 */
interface DocumentRepositoryInterface
{
    /**
     * @param int $documentId
     * @return DocumentInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $documentId): DocumentInterface;

    /**
     * @param string $documentCode
     * @return DocumentInterface
     * @throws NoSuchEntityException
     */
    public function getByCode(string $documentCode): DocumentInterface;

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return DocumentSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;

    /**
     * @param DocumentInterface $document
     * @return DocumentInterface
     * @throws CouldNotSaveException
     */
    public function save(DocumentInterface $document): DocumentInterface;

    /**
     * @param DocumentInterface $document
     * @throws CouldNotDeleteException
     */
    public function delete(DocumentInterface $document): void;
}
