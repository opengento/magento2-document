<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Api\Data;

use DateTime;
use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * @api
 */
interface DocumentTypeInterface extends ExtensibleDataInterface
{
    public function getId(): ?int;

    public function getCode(): string;

    public function getScheduledImport(): bool;

    public function getVisibility(): string;

    public function getName(): string;

    public function getFileSourcePath(): string;

    public function getFileDestPath(): string;

    public function getSubPathLength(): int;

    public function getFilePattern(): string;

    public function getFileAllowedExtensions(): array;

    public function getDefaultImageFileName(): string;

    public function getCreatedAt(): DateTime;

    public function getUpdatedAt(): DateTime;

    /**
     * @return \Opengento\Document\Api\Data\DocumentTypeExtensionInterface
     */
    public function getExtensionAttributes(): DocumentTypeExtensionInterface;

    /**
     * @param \Opengento\Document\Api\Data\DocumentTypeExtensionInterface $extensionAttributes
     * @return \Opengento\Document\Api\Data\DocumentTypeInterface
     */
    public function setExtensionAttributes(DocumentTypeExtensionInterface $extensionAttributes): DocumentTypeInterface;
}
