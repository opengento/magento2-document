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
interface DocumentInterface extends ExtensibleDataInterface
{
    public function getId(): ?int;

    public function getTypeId(): int;

    public function getCode(): string;

    public function getName(): string;

    public function getDescription(): string;

    public function getLocale(): ?string;

    public function getFileName(): string;

    public function getFilePath(): string;

    public function getImageFileName(): string;

    public function getCreatedAt(): DateTime;

    public function getUpdatedAt(): DateTime;

    /**
     * @return \Opengento\Document\Api\Data\DocumentExtensionInterface
     */
    public function getExtensionAttributes(): DocumentExtensionInterface;

    /**
     * @param \Opengento\Document\Api\Data\DocumentExtensionInterface $extensionAttributes
     * @return \Opengento\Document\Api\Data\DocumentInterface
     */
    public function setExtensionAttributes(DocumentExtensionInterface $extensionAttributes): DocumentInterface;
}
