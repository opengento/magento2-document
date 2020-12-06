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
use Opengento\Document\Api\Data\DocumentInterface;
use Opengento\Document\Api\Data\DocumentInterfaceFactory;

final class DocumentBuilder implements SimpleBuilderInterface
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var DocumentInterfaceFactory
     */
    private $documentFactory;

    public function __construct(
        DocumentInterfaceFactory $documentFactory
    ) {
        $this->documentFactory = $documentFactory;
        $this->data = [];
    }

    public function setId(int $entityId): self
    {
        $this->data['entity_id'] = $entityId;

        return $this;
    }

    public function setTypeId(int $typeId): self
    {
        $this->data['type_id'] = $typeId;

        return $this;
    }

    public function setCode(string $code): self
    {
        $this->data['code'] = $code;

        return $this;
    }

    public function setName(string $name): self
    {
        $this->data['name'] = $name;

        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->data['description'] = $description;

        return $this;
    }

    public function setLocale(string $locale): self
    {
        $this->data['file_locale'] = $locale;

        return $this;
    }

    public function setFileName(string $fileName): self
    {
        $this->data['file_name'] = $fileName;

        return $this;
    }

    public function setFilePath(string $filePath): self
    {
        $this->data['file_path'] = $filePath;

        return $this;
    }

    public function setImageFileName(string $imageFileName): self
    {
        $this->data['image_file_name'] = $imageFileName;

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

    public function create(): DocumentInterface
    {
        /** @var DocumentInterface $document */
        $document = $this->documentFactory->create(['data' => $this->getData()]);
        if ($document instanceof AbstractModel) {
            $document->setHasDataChanges(true);
        }

        return $document;
    }
}
