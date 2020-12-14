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
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Phrase;
use Opengento\Document\Api\Data\DocumentTypeInterface;
use Opengento\Document\Api\Data\DocumentTypeInterfaceFactory;
use Opengento\Document\Api\DocumentTypeRepositoryInterface;
use Opengento\Document\Controller\Adminhtml\Type\Save\HandlerInterface;

class Save extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Opengento_Document::document_type_save';

    /**
     * @var DocumentTypeRepositoryInterface
     */
    private $docTypeRepository;

    /**
     * @var DocumentTypeInterfaceFactory
     */
    private $documentTypeFactory;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var HandlerInterface
     */
    private $saveHandler;

    public function __construct(
        Context $context,
        DocumentTypeRepositoryInterface $docTypeRepository,
        DocumentTypeInterfaceFactory $documentTypeFactory,
        DataPersistorInterface $dataPersistor,
        HandlerInterface $saveHandler
    ) {
        $this->docTypeRepository = $docTypeRepository;
        $this->documentTypeFactory = $documentTypeFactory;
        $this->dataPersistor = $dataPersistor;
        $this->saveHandler = $saveHandler;
        parent::__construct($context);
    }

    public function execute()
    {
        $entityId = (int) $this->getRequest()->getParam('entity_id');
        $this->dataPersistor->set('document_type_post_data', $this->getRequest()->getParams());

        try {
            $entityId = $this->saveHandler->execute($this->getRequest(), $this->resolveDocumentType())->getId();
            $this->dataPersistor->clear('document_type_post_data');
            $this->messageManager->addSuccessMessage(new Phrase('The document type has been successfully saved.'));
        } catch (CouldNotSaveException $e) {
            $this->messageManager->addErrorMessage($e->getPrevious()->getMessage());
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (Exception $e) {
            $this->messageManager->addExceptionMessage($e, new Phrase('An error occurred on the server.'));
        }

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('*/*/');

        if ($this->dataPersistor->get('document_type_post_data') || $this->getRequest()->getParam('back')) {
            $resultRedirect->setPath('*/*/edit', ['id' => $entityId]);
        }

        return $resultRedirect;
    }

    /**
     * @return DocumentTypeInterface
     * @throws NoSuchEntityException
     */
    private function resolveDocumentType(): DocumentTypeInterface
    {
        $entityId = (int) $this->getRequest()->getParam('id');

        return $entityId ? $this->docTypeRepository->getById($entityId) : $this->documentTypeFactory->create();
    }
}
