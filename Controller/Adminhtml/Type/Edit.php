<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Controller\Adminhtml\Type;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Phrase;
use Magento\Framework\View\Result\Page;
use Opengento\Document\Api\Data\DocumentTypeInterfaceFactory;
use Opengento\Document\Api\DocumentTypeRepositoryInterface;
use Opengento\Document\Model\DocumentType\RegistryInterface;

class Edit extends Action
{
    public const ADMIN_RESOURCE = 'Opengento_Document::document_type_save';

    /**
     * @var RegistryInterface
     */
    private $registry;

    /**
     * @var DocumentTypeRepositoryInterface
     */
    private $docTypeRepository;

    /**
     * @var DocumentTypeInterfaceFactory
     */
    private $documentTypeFactory;

    public function __construct(
        Context $context,
        RegistryInterface $registry,
        DocumentTypeRepositoryInterface $docTypeRepository,
        DocumentTypeInterfaceFactory $documentTypeFactory
    ) {
        $this->registry = $registry;
        $this->docTypeRepository = $docTypeRepository;
        $this->documentTypeFactory = $documentTypeFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        try {
            $documentType = $this->getRequest()->getParam('id')
                ? $this->docTypeRepository->getById((int) $this->getRequest()->getParam('id'))
                : $this->documentTypeFactory->create();
            $this->registry->set($documentType);
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());

            /** @var Redirect $resultRedirect */
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setPath('*/*/');

            return $resultRedirect;
        }

        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Opengento_Document::document_type');
        $resultPage->getConfig()->getTitle()->prepend(
            $documentType->getId() ? $documentType->getName() : new Phrase('New Document Type')
        );

        return $resultPage;
    }
}
