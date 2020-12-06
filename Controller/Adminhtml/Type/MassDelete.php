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
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\Ui\Component\MassAction\Filter;
use Opengento\Document\Api\Data\DocumentTypeInterface;
use Opengento\Document\Api\DocumentTypeRepositoryInterface;
use Opengento\Document\Model\DocumentType\Authorization;
use Opengento\Document\Model\ResourceModel\DocumentType\CollectionFactory;

class MassDelete extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Opengento_Document::document_type_delete';

    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

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
        Filter $filter,
        CollectionFactory $collectionFactory,
        DocumentTypeRepositoryInterface $docTypeRepository,
        Authorization $authorization
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->docTypeRepository = $docTypeRepository;
        $this->authorization = $authorization;
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
        $count = 0;

        /** @var DocumentTypeInterface $document */
        foreach ($collection->getItems() as $document) {
            if ($this->authorization->isReadonly($document->getCode())) {
                $this->messageManager->addErrorMessage(
                    new Phrase(
                        'The document type with ID "%1" has not been deleted because it is in read-only mode.',
                        [$document->getId()]
                    )
                );
            } else {
                $this->docTypeRepository->delete($document);
                $count++;
            }

        }

        $this->messageManager->addSuccessMessage(new Phrase('A total of %1 record(s) have been deleted.', [$count]));
    }
}
