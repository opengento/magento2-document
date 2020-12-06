<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model;

use Magento\Framework\Api\ExtensibleDataInterface;
use Magento\Framework\Api\ExtensionAttributesInterface;
use Magento\Framework\Api\SimpleBuilderInterface;
use Magento\Framework\Model\AbstractModel;
use Opengento\Document\Api\Data\DocumentTypeInterface;
use Opengento\Document\Api\Data\DocumentTypeInterfaceFactory;
use function implode;

final class DocumentTypeBuilder implements SimpleBuilderInterface
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var DocumentTypeInterfaceFactory
     */
    private $documentTypeFactory;

    public function __construct(
        DocumentTypeInterfaceFactory $documentTypeFactory
    ) {
        $this->documentTypeFactory = $documentTypeFactory;
        $this->data = [];
    }

    public function setId(int $entityId): self
    {
        $this->data['entity_id'] = $entityId;

        return $this;
    }

    public function setCode(string $code): self
    {
        $this->data['code'] = $code;

        return $this;
    }

    public function setScheduledImport(bool $scheduleImport): self
    {
        $this->data['scheduled_import'] = (int) $scheduleImport;

        return $this;
    }

    public function setName(string $name): self
    {
        $this->data['name'] = $name;

        return $this;
    }

    public function setFileSourcePath(string $fileSourcePath): self
    {
        $this->data['file_source_path'] = $fileSourcePath;

        return $this;
    }

    public function setFileDestPath(string $fileDestPath): self
    {
        $this->data['file_dest_path'] = $fileDestPath;

        return $this;
    }

    public function setSubPathLength(int $subPathLength): self
    {
        $this->data['sub_path_length'] = $subPathLength;

        return $this;
    }

    public function setFilePattern(string $filePattern): self
    {
        $this->data['file_pattern'] = $filePattern;

        return $this;
    }

    public function setFileAllowedExtensions(array $allowedExtensions): self
    {
        $this->data['file_allowed_extensions'] = implode(',', $allowedExtensions);
        $this->data['file_allowed_extensions_array'] = $allowedExtensions;

        return $this;
    }

    public function setDefaultImageFileName(string $imageFileName): self
    {
        $this->data['default_image_file_name'] = $imageFileName;

        return $this;
    }

    public function setExtensionAttributes(ExtensionAttributesInterface $extensionAttributes): self
    {
        $this->data[ExtensibleDataInterface::EXTENSION_ATTRIBUTES_KEY] = $extensionAttributes;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function create(): DocumentTypeInterface
    {
        /** @var DocumentTypeInterface $documentType */
        $documentType = $this->documentTypeFactory->create(['data' => $this->getData()]);
        if ($documentType instanceof AbstractModel) {
            $documentType->setHasDataChanges(true);
        }

        return $documentType;
    }
}
