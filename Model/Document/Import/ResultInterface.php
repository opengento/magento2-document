<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Document\Import;

/**
 * @api
 */
interface ResultInterface
{
    public function count(): int;

    public function getMessages(): array;
}
