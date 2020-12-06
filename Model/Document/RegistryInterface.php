<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Document;

use Opengento\Document\Api\Data\DocumentInterface;

/**
 * @æpi
 */
interface RegistryInterface
{
    public function get(): DocumentInterface;

    public function set(DocumentInterface $document): void;
}
