<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Document;

use Exception;
use Magento\Framework\Phrase;
use Opengento\Document\Api\Data\DocumentInterface;
use Opengento\Document\Api\Data\DocumentTypeInterface;
use Opengento\Document\Model\Document\Helper\File;
use Opengento\Document\Model\Document\Import\StatusUpdaterInterface;
use Opengento\Document\Model\Document\Operation\CreateFromFileInterface;
use Psr\Log\LoggerInterface;
use function count;
use const GLOB_NOSORT;

final class Import implements ImportInterface
{
    /**
     * @var CreateFromFileInterface
     */
    private $createFromFile;

    /**
     * @var ImportResultsInterfaceFactory
     */
    private $resultsFactory;

    /**
     * @var File
     */
    private $fileHelper;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var StatusUpdaterInterface[]
     */
    public $statusUpdaters;

    public function __construct(
        CreateFromFileInterface $createFromFile,
        ImportResultsInterfaceFactory $resultsFactory,
        File $fileHelper,
        LoggerInterface $logger,
        array $statusUpdaters = []
    ) {
        $this->createFromFile = $createFromFile;
        $this->resultsFactory = $resultsFactory;
        $this->fileHelper = $fileHelper;
        $this->logger = $logger;
        $this->statusUpdaters = $statusUpdaters;
    }

    public function execute(DocumentTypeInterface $documentType): ImportResultsInterface
    {
        $results = ['errors' => [], 'success' => []];
        $files = $this->fileHelper->lookupFiles($documentType, GLOB_NOSORT);

        $this->updateStatusStart(count($files));

        foreach ($files as $file) {
            try {
                $document = $this->createFromFile->execute($documentType, $file);
                $results['success'][] = new Phrase(
                    'Document "%1" with ID "%2" is successfully imported.',
                    [$document->getId(), $document->getFileName()]
                );
            } catch (Exception $e) {
                $this->logger->error($e->getMessage(), $e->getTrace());
                $results['errors'][] = $e->getMessage();
            } finally {
                $this->updateStatusAdvance($file, $document ?? null);
            }
        }

        $this->updateStatusFinish();

        return $this->resultsFactory->create($results);
    }

    private function updateStatusStart(int $units): void
    {
        foreach ($this->statusUpdaters as $statusUpdater) {
            $statusUpdater->start($units);
        }
    }

    private function updateStatusAdvance(string $file, ?DocumentInterface $document): void
    {
        foreach ($this->statusUpdaters as $statusUpdater) {
            $statusUpdater->advance($file, $document);
        }
    }

    private function updateStatusFinish(): void
    {
        foreach ($this->statusUpdaters as $statusUpdater) {
            $statusUpdater->finish();
        }
    }
}
