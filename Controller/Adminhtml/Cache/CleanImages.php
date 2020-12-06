<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Controller\Adminhtml\Cache;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Opengento\Document\Model\Cache\Image;

class CleanImages extends Action
{
    public const ADMIN_RESOURCE = 'Opengento_Document::flush_images';

    /**
     * @var Image
     */
    private $imageCache;

    public function __construct(
        Context $context,
        Image $imageCache
    ) {
        $this->imageCache = $imageCache;
        parent::__construct($context);
    }

    public function execute()
    {
        try {
            $this->imageCache->clear();
            $this->messageManager->addSuccessMessage(new Phrase('The image cache was cleaned.'));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (Exception $e) {
            $this->messageManager->addExceptionMessage($e, new Phrase('An error occurred while clearing the image cache.'));
        }

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $resultRedirect->setPath('adminhtml/*');
    }
}
