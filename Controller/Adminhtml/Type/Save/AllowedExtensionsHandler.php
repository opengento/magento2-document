<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Controller\Adminhtml\Type\Save;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\EntityManager\HydratorPool;
use Opengento\Document\Api\Data\DocumentTypeInterface;
use function array_column;
use function implode;

final class AllowedExtensionsHandler implements HandlerInterface
{
    /**
     * @var HydratorPool
     */
    private $hydratorPool;

    public function __construct(
        HydratorPool $hydratorPool
    ) {
        $this->hydratorPool = $hydratorPool;
    }

    public function execute(RequestInterface $request, DocumentTypeInterface $documentType): DocumentTypeInterface
    {
        $allowedExtensions = implode(',', array_column($request->getParam('file_allowed_extensions'), 'extension'));
        $hydrator = $this->hydratorPool->getHydrator(DocumentTypeInterface::class);

        return $hydrator->hydrate($documentType, ['file_allowed_extensions' => $allowedExtensions]);
    }

    public function rollback(RequestInterface $request): void
    {
        /** Silence is golden... */
    }
}
