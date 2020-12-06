<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Controller\Adminhtml\Index\Save;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\EntityManager\HydratorPool;
use Opengento\Document\Api\Data\DocumentInterface;
use function array_filter;
use function array_intersect_key;

final class DefaultHandler implements HandlerInterface
{
    /**
     * @var HydratorPool
     */
    private $hydratorPool;

    /**
     * @var string[]
     */
    private $allowedFields;

    public function __construct(
        HydratorPool $hydratorPool,
        array $allowedFields
    ) {
        $this->hydratorPool = $hydratorPool;
        $this->allowedFields = $allowedFields;
    }

    public function execute(RequestInterface $request, DocumentInterface $document): DocumentInterface
    {
        $hydrator = $this->hydratorPool->getHydrator(DocumentInterface::class);

        return $hydrator->hydrate(
            $document,
            array_intersect_key($request->getParams(), array_filter($this->allowedFields))
        );
    }

    public function rollback(RequestInterface $request): void
    {
        /** Silence is golden... */
    }
}
