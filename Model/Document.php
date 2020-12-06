<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model;

use DateTime;
use Exception;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractExtensibleModel;
use Opengento\Document\Api\Data\DocumentExtensionInterface;
use Opengento\Document\Api\Data\DocumentInterface;

class Document extends AbstractExtensibleModel implements DocumentInterface, IdentityInterface
{
    public const CACHE_TAG = 'opengento_document';

    protected function _construct(): void
    {
        $this->_init(ResourceModel\Document::class);
        $this->_cacheTag = self::CACHE_TAG;
        $this->_eventPrefix = self::CACHE_TAG;
    }

    public function getIdentities(): array
    {
        return [
            self::CACHE_TAG . '_' . $this->getId(),
            DocumentType::CACHE_TAG . '_' . $this->getTypeId(),
        ];
    }

    public function getId(): ?int
    {
        return parent::getId() ? (int) parent::getId() : null;
    }

    public function getTypeId(): int
    {
        return (int) $this->_getData('type_id');
    }

    public function getCode(): string
    {
        return (string) $this->_getData('code');
    }

    public function getName(): string
    {
        return (string) $this->_getData('name');
    }

    public function getDescription(): string
    {
        return (string) $this->_getData('description');
    }

    public function getLocale(): ?string
    {
        return $this->_getData('file_locale') ? (string) $this->_getData('file_locale') : null;
    }

    public function getFileName(): string
    {
        return (string) $this->_getData('file_name');
    }

    public function getFilePath(): string
    {
        return (string) $this->_getData('file_path');
    }

    public function getImageFileName(): string
    {
        return (string) $this->_getData('image_file_name');
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function getCreatedAt(): DateTime
    {
        return new DateTime($this->_getData('created_at'));
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function getUpdatedAt(): DateTime
    {
        return new DateTime($this->_getData('updated_at'));
    }

    public function getExtensionAttributes(): DocumentExtensionInterface
    {
        if (!$this->_getExtensionAttributes()) {
            $this->setExtensionAttributes($this->extensionAttributesFactory->create(DocumentInterface::class));
        }

        return $this->_getExtensionAttributes();
    }

    public function setExtensionAttributes(DocumentExtensionInterface $extensionAttributes): DocumentInterface
    {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
