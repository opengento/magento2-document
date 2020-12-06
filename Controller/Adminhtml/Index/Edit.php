<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Phrase;
use Magento\Framework\View\Result\Page;
use Opengento\Document\Api\Data\DocumentInterfaceFactory;
use Opengento\Document\Api\DocumentRepositoryInterface;
use Opengento\Document\Model\Document\RegistryInterface;

class Edit extends Action
{
    public const ADMIN_RESOURCE = 'Opengento_Document::document_save';

    /**
     * @var RegistryInterface
     */
    private $registry;

    /**
     * @var DocumentRepositoryInterface
     */
    private $documentRepository;

    /**
     * @var DocumentInterfaceFactory
     */
    private $documentFactory;

    public function __construct(
        Context $context,
        RegistryInterface $registry,
        DocumentRepositoryInterface $documentRepository,
        DocumentInterfaceFactory $documentFactory
    ) {
        $this->registry = $registry;
        $this->documentRepository = $documentRepository;
        $this->documentFactory = $documentFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        try {
            $document = $this->getRequest()->getParam('id')
                ? $this->documentRepository->getById((int) $this->getRequest()->getParam('id'))
                : $this->documentFactory->create();
            $this->registry->set($document);
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());

            /** @var Redirect $resultRedirect */
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setPath('*/*/');

            return $resultRedirect;
        }

        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Opengento_Document::document');
        $resultPage->getConfig()->getTitle()->prepend(
            $document->getId() ? $document->getName() : new Phrase('New Document')
        );

        return $resultPage;
    }
}
