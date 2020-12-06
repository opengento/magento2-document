<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Cron;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Opengento\Document\Api\DocumentTypeRepositoryInterface;
use Opengento\Document\Model\Document\ImportInterface;
use Psr\Log\LoggerInterface;

final class ImportDocuments
{
    /**
     * @var DocumentTypeRepositoryInterface
     */
    private $docTypeRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $criteriaBuilder;

    /**
     * @var ImportInterface
     */
    private $import;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        DocumentTypeRepositoryInterface $docTypeRepository,
        SearchCriteriaBuilder $criteriaBuilder,
        ImportInterface $import,
        LoggerInterface $logger
    ) {
        $this->docTypeRepository = $docTypeRepository;
        $this->criteriaBuilder = $criteriaBuilder;
        $this->import = $import;
        $this->logger = $logger;
    }

    public function execute(): void
    {
        $this->criteriaBuilder->addFilter('scheduled_import', true);
        try {
            $documentTypeList = $this->docTypeRepository->getList($this->criteriaBuilder->create());
        } catch (LocalizedException $e) {
            $this->logger->error($e->getLogMessage(), $e->getTrace());

            return;
        }

        foreach ($documentTypeList->getItems() as $documentType) {
            $results = $this->import->execute($documentType);
            foreach ($results->getErrorResult()->getMessages() as $message) {
                $this->logger->error($message);
            }
        }
    }
}
