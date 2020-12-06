<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Exception;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;

/**
 * @api
 */
final class InvalidTypeException extends LocalizedException
{
    public static function invalidTypeException(string $fileName, string $type): InvalidTypeException
    {
        return new self(new Phrase('"%1" is not a valid type of "%2".', [$fileName, $type]));
    }
}
