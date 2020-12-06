<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Console\Command;

use Generator;
use Magento\Framework\Console\Cli;
use Magento\Framework\Exception\NoSuchEntityException;
use Opengento\Document\Api\Data\DocumentTypeInterface;
use Opengento\Document\Api\DocumentTypeRepositoryInterface;
use Opengento\Document\Model\Document\Import\StatusUpdaterInterface;
use Opengento\Document\Model\Document\ImportInterface;
use Opengento\Document\Model\Document\ImportInterfaceFactory;
use Opengento\Document\Model\DocumentTypeBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use function microtime;

class ImportDocuments extends Command
{
    private const INPUT_DOCUMENT_TYPE_CODE = 'type';
    private const INPUT_FILE_SOURCE_PATH = 'file_source_path';
    private const INPUT_FILE_PATTERN = 'file_pattern';

    /**
     * @var DocumentTypeRepositoryInterface
     */
    private $docTypeRepository;

    /**
     * @var DocumentTypeBuilder
     */
    private $docTypeBuilder;

    /**
     * @var ImportInterface
     */
    private $import;

    /**
     * @var StatusUpdaterInterface
     */
    private $statusUpdater;

    public function __construct(
        DocumentTypeRepositoryInterface $docTypeRepository,
        DocumentTypeBuilder $docTypeBuilder,
        ImportInterfaceFactory $importFactory,
        StatusUpdaterInterface $statusUpdater,
        string $name = 'document:import:file'
    ) {
        $this->docTypeRepository = $docTypeRepository;
        $this->docTypeBuilder = $docTypeBuilder;
        $this->import = $importFactory->create(['statusUpdaters' => [$statusUpdater]]);
        $this->statusUpdater = $statusUpdater;
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->setDescription('Import files by document type.')
            ->setDefinition([
                new InputArgument(
                    self::INPUT_DOCUMENT_TYPE_CODE,
                    InputArgument::REQUIRED + InputArgument::IS_ARRAY,
                    ''
                ),
                new InputOption(
                    self::INPUT_FILE_SOURCE_PATH,
                    's',
                    InputOption::VALUE_OPTIONAL,
                    'File Source Path'
                ),
                new InputOption(
                    self::INPUT_FILE_PATTERN,
                    'p',
                    InputOption::VALUE_OPTIONAL,
                    'File Pattern'
                )
            ]);

        parent::configure();
    }

    public function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $returnCode = Cli::RETURN_SUCCESS;

        $this->statusUpdater->init(['output' => $output]);

        foreach ($this->resolveDocumentType($input) as $documentType) {
            if ($documentType instanceof DocumentTypeInterface) {
                $output->writeln('<info>Start importing ' . $documentType->getName() . ' files...</info>');
                $this->import($documentType, $output);
            } elseif ($documentType instanceof NoSuchEntityException) {
                $output->writeln('<error>' . $documentType->getMessage() . '</error>');
                $returnCode = Cli::RETURN_FAILURE;
            }
        }

        return $returnCode;
    }

    private function resolveDocumentType(InputInterface $input): ?Generator
    {
        $documentTypeCodes = (array) $input->getArgument(self::INPUT_DOCUMENT_TYPE_CODE);

        foreach ($documentTypeCodes as $documentTypeCode) {
            try {
                yield $this->createCustomDocumentType($this->docTypeRepository->getByCode($documentTypeCode), $input);
            } catch (NoSuchEntityException $e) {
                yield $e;
            }
        }
    }

    private function createCustomDocumentType(
        DocumentTypeInterface $documentType,
        InputInterface $input
    ): DocumentTypeInterface {
        $this->docTypeBuilder->setId($documentType->getId());
        $this->docTypeBuilder->setDefaultImageFileName($documentType->getDefaultImageFileName());
        $this->docTypeBuilder->setFilePattern(
            $input->getOption(self::INPUT_FILE_PATTERN) ?? $documentType->getFilePattern()
        );
        $this->docTypeBuilder->setSubPathLength($documentType->getSubPathLength());
        $this->docTypeBuilder->setFileDestPath($documentType->getFileDestPath());
        $this->docTypeBuilder->setFileSourcePath(
            $input->getOption(self::INPUT_FILE_SOURCE_PATH) ?? $documentType->getFileSourcePath()
        );
        $this->docTypeBuilder->setCode($documentType->getCode());
        $this->docTypeBuilder->setName($documentType->getName());

        return $this->docTypeBuilder->create();
    }

    private function import(DocumentTypeInterface $documentType, OutputInterface $output): void
    {
        $startTime = microtime(true);
        $results = $this->import->execute($documentType);

        $output->writeln('<info>Import duration: ' . (microtime(true) - $startTime) . ' sec.</info>');
        $output->writeln(
            '<info>' . $results->getSuccessResult()->count() . ' document(s) were successfully imported.</info>'
        );

        if ($results->getErrorResult()->count()) {
            $output->writeln(
                '<error>' . $results->getErrorResult()->count() . ' error(s) occurred in the import:</error>'
            );
        }
        foreach ($results->getErrorResult()->getMessages() as $message) {
            $output->writeln('<error>' . $message . '</error>');
        }
    }
}
