<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\DocumentType;

use Opengento\Document\Api\Data\DocumentTypeInterface;

final class Registry implements RegistryInterface
{
    /**
     * @var DocumentTypeInterface|null
     */
    private $documentType;

    public function get(): DocumentTypeInterface
    {
        return $this->documentType;
    }

    public function set(DocumentTypeInterface $documentType): void
    {
        $this->documentType = $documentType;
    }
}
