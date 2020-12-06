<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Controller\Adminhtml\Type;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Phrase;
use Opengento\Document\Api\DocumentTypeRepositoryInterface;
use Opengento\Document\Model\DocumentType\Authorization;

class Delete extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Opengento_Document::document_type_delete';

    /**
     * @var DocumentTypeRepositoryInterface
     */
    private $docTypeRepository;

    /**
     * @var Authorization
     */
    private $authorization;

    public function __construct(
        Context $context,
        DocumentTypeRepositoryInterface $docTypeRepository,
        Authorization $authorization
    ) {
        $this->docTypeRepository = $docTypeRepository;
        $this->authorization = $authorization;
        parent::__construct($context);
    }

    public function execute()
    {
        try {
            $this->delete((int) $this->getRequest()->getParam('id'));
            $this->messageManager->addSuccessMessage(new Phrase('The document type has been successfully deleted.'));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (Exception $e) {
            $this->messageManager->addExceptionMessage($e, new Phrase('An error occurred on the server.'));
        }

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @param int $documentTypeId
     * @return void
     * @throws LocalizedException
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    private function delete(int $documentTypeId): void
    {
        $documentType = $this->docTypeRepository->getById($documentTypeId);

        if ($this->authorization->isReadonly($documentType->getCode())) {
            throw new LocalizedException(
                new Phrase('The document type have not been deleted because it is in read-only mode.')
            );
        }

        $this->docTypeRepository->delete($documentType);
    }
}
