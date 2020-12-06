<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\DocumentType;

/**
 * @api
 */
interface AuthorizationInterface
{
    public function isReadonly(string $code): bool;
}
