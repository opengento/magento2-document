<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Config\DocumentType;

use DOMElement;
use DOMNode;
use Magento\Framework\Api\SimpleDataObjectConverter;
use Magento\Framework\Config\ConverterInterface;
use Opengento\Document\Model\Config\DocumentType\Converter\NodeConverterInterface;

final class Converter implements ConverterInterface
{
    /**
     * @var NodeConverterInterface[]
     */
    private $nodeConverters;

    public function __construct(
        array $nodeConverters = []
    ) {
        $this->nodeConverters = $nodeConverters;
    }

    public function convert($source): array
    {
        if (!($source instanceof DOMNode)) {
            return [];
        }

        $documentTypes = [];
        $defaultConverter = $this->nodeConverters['default'];

        /** @var DOMElement $documentType */
        foreach ($source->getElementsByTagName('documentType') as $documentType) {
            $data = [];
            /** @var DOMNode $node */
            foreach ($documentType->childNodes as $node) {
                $converter = $this->nodeConverters[$node->nodeName] ?? $defaultConverter;
                $data[SimpleDataObjectConverter::camelCaseToSnakeCase($node->nodeName)] = $converter->convert($node);
            }

            $data['code'] = $documentType->getAttribute('code');
            $documentTypes[$data['code']] = $data;
        }

        return $documentTypes;
    }
}
