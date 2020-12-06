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
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Opengento\Document\Api\DocumentRepositoryInterface;

class Delete extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Opengento_Document::document_delete';

    /**
     * @var DocumentRepositoryInterface
     */
    private $documentRepository;

    public function __construct(
        Context $context,
        DocumentRepositoryInterface $documentRepository
    ) {
        $this->documentRepository = $documentRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        try {
            $this->documentRepository->delete(
                $this->documentRepository->getById((int) $this->getRequest()->getParam('id'))
            );
            $this->messageManager->addSuccessMessage(new Phrase('The document has been successfully deleted.'));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (Exception $e) {
            $this->messageManager->addExceptionMessage($e, new Phrase('An error occurred on the server.'));
        }

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/');
    }
}
