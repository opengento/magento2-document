<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Document\Import;

use Opengento\Document\Api\Data\DocumentInterface;

/**
 * @api
 */
interface StatusUpdaterInterface
{
    public function init(array $arguments = []): void;

    public function start(int $units): void;

    public function advance(string $file, ?DocumentInterface $document): void;

    public function finish(): void;
}
