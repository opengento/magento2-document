<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Controller\Adminhtml\Index;

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
use Opengento\Document\Api\Data\DocumentInterface;
use Opengento\Document\Api\Data\DocumentInterfaceFactory;
use Opengento\Document\Api\DocumentRepositoryInterface;
use Opengento\Document\Controller\Adminhtml\Index\Save\HandlerInterface;

class Save extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Opengento_Document::document_save';

    /**
     * @var DocumentRepositoryInterface
     */
    private $documentRepository;

    /**
     * @var DocumentInterfaceFactory
     */
    private $documentFactory;

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
        DocumentRepositoryInterface $documentRepository,
        DocumentInterfaceFactory $documentFactory,
        DataPersistorInterface $dataPersistor,
        HandlerInterface $saveHandler
    ) {
        $this->documentRepository = $documentRepository;
        $this->documentFactory = $documentFactory;
        $this->dataPersistor = $dataPersistor;
        $this->saveHandler = $saveHandler;
        parent::__construct($context);
    }

    public function execute()
    {
        $entityId = (int) $this->getRequest()->getParam('entity_id');
        $this->dataPersistor->set('document_post_data', $this->getRequest()->getParams());

        try {
            $entityId = $this->saveHandler->execute($this->getRequest(), $this->resolveDocument())->getId();
            $this->dataPersistor->clear('document_post_data');
            $this->messageManager->addSuccessMessage(new Phrase('The document has been successfully saved.'));
        } catch (CouldNotSaveException $e) {
            $this->messageManager->addErrorMessage($e->getPrevious()->getMessage());
            $this->saveHandler->rollback($this->getRequest());
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $this->saveHandler->rollback($this->getRequest());
        } catch (Exception $e) {
            $this->messageManager->addExceptionMessage($e, new Phrase('An error occurred on the server.'));
            $this->saveHandler->rollback($this->getRequest());
        }

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('*/*/');

        if ($this->dataPersistor->get('document_post_data') || $this->getRequest()->getParam('back')) {
            $resultRedirect->setPath('*/*/edit', ['id' => $entityId]);
        }

        return $resultRedirect;
    }

    /**
     * @return DocumentInterface
     * @throws NoSuchEntityException
     */
    private function resolveDocument(): DocumentInterface
    {
        $entityId = (int) $this->getRequest()->getParam('entity_id');

        return $entityId ? $this->documentRepository->getById($entityId) : $this->documentFactory->create();
    }
}
