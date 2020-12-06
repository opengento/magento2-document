<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Controller\Adminhtml\Index\Save;

use Magento\Framework\App\RequestInterface;
use Opengento\Document\Api\Data\DocumentInterface;
use Opengento\Document\Api\DocumentRepositoryInterface;

final class ProcessHandler implements HandlerInterface
{
    /**
     * @var DocumentRepositoryInterface
     */
    private $documentRepository;

    /**
     * @var HandlerInterface[]
     */
    private $handlers;

    public function __construct(
        DocumentRepositoryInterface $documentRepository,
        array $handlers
    ) {
        $this->documentRepository = $documentRepository;
        $this->handlers = $handlers;
    }

    public function execute(RequestInterface $request, DocumentInterface $document): DocumentInterface
    {
        foreach ($this->handlers as $handler) {
            $document = $handler->execute($request, $document);
        }

        return $this->documentRepository->save($document);
    }

    public function rollback(RequestInterface $request): void
    {
        foreach ($this->handlers as $handler) {
            $handler->rollback($request);
        }
    }
}
