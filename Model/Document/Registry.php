<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Document;

use Opengento\Document\Api\Data\DocumentInterface;

final class Registry implements RegistryInterface
{
    /**
     * @var DocumentInterface|null
     */
    private $document;

    public function get(): DocumentInterface
    {
        return $this->document;
    }

    public function set(DocumentInterface $document): void
    {
        $this->document = $document;
    }
}
