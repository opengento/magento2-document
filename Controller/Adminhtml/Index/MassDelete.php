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
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\Ui\Component\MassAction\Filter;
use Opengento\Document\Api\Data\DocumentInterface;
use Opengento\Document\Api\DocumentRepositoryInterface;
use Opengento\Document\Model\ResourceModel\Document\CollectionFactory;

class MassDelete extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Opengento_Document::document_delete';

    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var DocumentRepositoryInterface
     */
    private $documentRepository;

    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        DocumentRepositoryInterface $documentRepository
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->documentRepository = $documentRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('*/*/');

        try {
            $this->massAction($this->filter->getCollection($this->collectionFactory->create()));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (Exception $e) {
            $this->messageManager->addExceptionMessage($e, $e->getMessage());
        }

        return $resultRedirect;
    }

    /**
     * @param AbstractDb $collection
     * @return void
     * @throws CouldNotDeleteException
     */
    private function massAction(AbstractDb $collection): void
    {
        /** @var DocumentInterface $document */
        foreach ($collection->getItems() as $document) {
            $this->documentRepository->delete($document);
        }

        $this->messageManager->addSuccessMessage(
            new Phrase('A total of %1 record(s) has been deleted.', [$collection->count()])
        );
    }
}
