<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Config\DocumentType\Converter;

use DOMNode;

interface NodeConverterInterface
{
    public function convert(DOMNode $node): string;
}
