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

        /** @var DOMElement $documentType */
        foreach ($source->getElementsByTagName('documentType') as $documentType) {
            $data = [];
            /** @var DOMNode $node */
            foreach ($documentType->childNodes as $node) {
                $data[SimpleDataObjectConverter::camelCaseToSnakeCase($node->nodeName)] = $this->convertNode($node);
            }

            $data['code'] = $documentType->getAttribute('code');
            $documentTypes[$data['code']] = $data;
        }

        return $documentTypes;
    }

    private function convertNode(DOMNode $node): string
    {
        return ($this->nodeConverters[$node->nodeName] ?? $this->nodeConverters['default'])->convert($node);
    }
}
