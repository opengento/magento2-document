<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Config\DocumentType\Converter;

use DOMNode;
use Magento\Framework\Stdlib\BooleanUtils;

final class ScheduledImportConverter implements NodeConverterInterface
{
    /**
     * @var BooleanUtils
     */
    private $booleanUtils;

    public function __construct(
        BooleanUtils $booleanUtils
    ) {
        $this->booleanUtils = $booleanUtils;
    }

    public function convert(DOMNode $node): bool
    {
        return $this->booleanUtils->toBoolean($node->nodeValue);
    }
}
