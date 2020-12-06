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
use Opengento\Document\Api\Data\DocumentTypeInterface;
use Opengento\Document\Api\Data\DocumentTypeSearchResultsInterface;

/**
 * @api
 */
interface DocumentTypeRepositoryInterface
{
    /**
     * @param int $documentTypeId
     * @return DocumentTypeInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $documentTypeId): DocumentTypeInterface;

    /**
     * @param string $documentTypeCode
     * @return DocumentTypeInterface
     * @throws NoSuchEntityException
     */
    public function getByCode(string $documentTypeCode): DocumentTypeInterface;

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return DocumentTypeSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;

    /**
     * @param DocumentTypeInterface $documentType
     * @return DocumentTypeInterface
     * @throws CouldNotSaveException
     */
    public function save(DocumentTypeInterface $documentType): DocumentTypeInterface;

    /**
     * @param DocumentTypeInterface $documentType
     * @throws CouldNotDeleteException
     */
    public function delete(DocumentTypeInterface $documentType): void;
}
