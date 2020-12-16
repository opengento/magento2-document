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
use Opengento\Document\Api\Data\DocumentTypeExtensionInterface;
use Opengento\Document\Api\Data\DocumentTypeInterface;
use function explode;

class DocumentType extends AbstractExtensibleModel implements DocumentTypeInterface, IdentityInterface
{
    public const CACHE_TAG = 'ope_dt';

    protected function _construct(): void
    {
        $this->_init(ResourceModel\DocumentType::class);
        $this->_cacheTag = self::CACHE_TAG;
        $this->_eventPrefix = self::CACHE_TAG;
    }

    public function getIdentities(): array
    {
        return [self::CACHE_TAG, self::CACHE_TAG . '_' . $this->getId(), self::CACHE_TAG . '_' . $this->getCode()];
    }

    public function getCacheTags(): array
    {
        return $this->getIdentities() ?: parent::getCacheTags();
    }

    public function getId(): ?int
    {
        return parent::getId() ? (int)  parent::getId() : null;
    }

    public function getCode(): string
    {
        return (string) $this->_getData('code');
    }

    public function getScheduledImport(): bool
    {
        return (bool) $this->_getData('scheduled_import');
    }

    public function getVisibility(): string
    {
        return (string) $this->_getData('visibility');
    }

    public function getName(): string
    {
        return (string) $this->_getData('name');
    }

    public function getFileSourcePath(): string
    {
        return (string) $this->_getData('file_source_path');
    }

    public function getFileDestPath(): string
    {
        return (string) $this->_getData('file_dest_path');
    }

    public function getSubPathLength(): int
    {
        return (int) $this->_getData('sub_path_length');
    }

    public function getFilePattern(): string
    {
        return (string) $this->_getData('file_pattern');
    }

    public function getFileAllowedExtensions(): array
    {
        if (!$this->hasData('file_allowed_extensions_array')) {
            $this->setData('file_allowed_extensions_array', explode(',', $this->_getData('file_allowed_extensions')));
        }

        return (array) $this->_getData('file_allowed_extensions_array');
    }

    public function getDefaultImageFileName(): string
    {
        return (string) $this->_getData('default_image_file_name');
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

    public function getExtensionAttributes(): DocumentTypeExtensionInterface
    {
        if (!$this->_getExtensionAttributes()) {
            $this->setExtensionAttributes($this->extensionAttributesFactory->create(DocumentTypeInterface::class));
        }

        return $this->_getExtensionAttributes();
    }

    public function setExtensionAttributes(DocumentTypeExtensionInterface $extensionAttributes): DocumentTypeInterface
    {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
