<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Controller\Adminhtml\Type\Save;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Opengento\Document\Api\Data\DocumentTypeInterface;
use Opengento\Document\Api\DocumentTypeRepositoryInterface;
use Opengento\Document\Model\DocumentType\Authorization;

final class ProcessHandler implements HandlerInterface
{
    /**
     * @var DocumentTypeRepositoryInterface
     */
    private $docTypeRepository;

    /**
     * @var Authorization
     */
    private $authorization;

    /**
     * @var HandlerInterface[]
     */
    private $handlers;

    public function __construct(
        DocumentTypeRepositoryInterface $docTypeRepository,
        Authorization $authorization,
        array $handlers
    ) {
        $this->docTypeRepository = $docTypeRepository;
        $this->authorization = $authorization;
        $this->handlers = $handlers;
    }

    public function execute(RequestInterface $request, DocumentTypeInterface $documentType): DocumentTypeInterface
    {
        if ($this->authorization->isReadonly($documentType->getCode())) {
            throw new LocalizedException(
                new Phrase('The document type has not been saved because it is in read-only mode.')
            );
        }

        foreach ($this->handlers as $handler) {
            $documentType = $handler->execute($request, $documentType);
        }

        return $this->docTypeRepository->save($documentType);
    }

    public function rollback(RequestInterface $request): void
    {
        foreach ($this->handlers as $handler) {
            $handler->rollback($request);
        }
    }
}
