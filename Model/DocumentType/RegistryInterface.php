<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\DocumentType;

use Opengento\Document\Api\Data\DocumentTypeInterface;

/**
 * @æpi
 */
interface RegistryInterface
{
    public function get(): DocumentTypeInterface;

    public function set(DocumentTypeInterface $documentType): void;
}
