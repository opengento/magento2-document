<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Config\DocumentType\Converter;

use DOMNode;
use function array_filter;
use function implode;
use function trim;

final class FileAllowedExtensionsConverter implements NodeConverterInterface
{
    public function convert(DOMNode $node): string
    {
        $allowedExtensions = [];
        /** @var DOMNode $childNode */
        foreach ($node->childNodes as $childNode) {
            $allowedExtensions[] = trim($childNode->nodeValue);
        }

        return implode(',', array_filter($allowedExtensions));
    }
}
