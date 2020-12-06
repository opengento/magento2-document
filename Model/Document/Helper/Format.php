<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Document\Helper;

use function pathinfo;
use function preg_replace;
use function str_replace;
use function strtolower;
use function ucwords;
use const PATHINFO_FILENAME;

final class Format
{
    public static function formatCode(string $fileName): string
    {
        return strtolower(preg_replace('/\W+/', '', str_replace(' ', '_', pathinfo($fileName, PATHINFO_FILENAME))));
    }

    public static function formatName(string $fileName): string
    {
        return ucwords(str_replace('_', ' ', pathinfo($fileName, PATHINFO_FILENAME)));
    }
}
