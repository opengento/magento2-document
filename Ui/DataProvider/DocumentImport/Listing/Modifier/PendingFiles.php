<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Ui\DataProvider\DocumentImport\Listing\Modifier;

use Magento\Framework\Filesystem\Glob;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Opengento\Document\Api\Data\DocumentTypeInterface;
use Opengento\Document\Api\Data\DocumentTypeInterfaceFactory;
use Opengento\Document\Model\Document\Filesystem\File as FileHelper;
use function count;

final class PendingFiles implements ModifierInterface
{
    /**
     * @var FileHelper
     */
    private $fileHelper;

    /**
     * @var DocumentTypeInterfaceFactory
     */
    private $docTypeFactory;

    public function __construct(
        FileHelper $fileHelper,
        DocumentTypeInterfaceFactory $docTypeFactory
    ) {
        $this->fileHelper = $fileHelper;
        $this->docTypeFactory = $docTypeFactory;
    }

    public function modifyData(array $data): array
    {
        if (isset($data['items'])) {
            foreach ($data['items'] as $key => $item) {
                $data['items'][$key]['total_pending'] = $this->countFiles($item);
            }
        }

        return $data;
    }

    public function modifyMeta(array $meta): array
    {
        return $meta;
    }

    private function countFiles(array $item): int
    {
        /** @var DocumentTypeInterface $documentType */
        $documentType = $this->docTypeFactory->create(['data' => $item]);

        return count($this->fileHelper->lookupFiles($documentType, Glob::GLOB_NOSORT));
    }
}
