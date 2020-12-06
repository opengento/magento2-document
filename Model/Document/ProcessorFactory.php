<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Document;

use Magento\Framework\ObjectManagerInterface;

/**
 * @api
 */
final class ProcessorFactory
{
    private const DEFAULT_PROCESSOR = 'simple';

    /**
     * @var string[]
     */
    private $processors;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    public function __construct(
        array $processors,
        ObjectManagerInterface $objectManager
    ) {
        $this->processors = $processors;
        $this->objectManager = $objectManager;
    }

    public function get(string $processorCode): ProcessorInterface
    {
        return $this->objectManager->get($this->processors[$processorCode] ?? $this->processors[self::DEFAULT_PROCESSOR]);
    }
}
