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
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\InputException;
use Opengento\Document\Exception\UploadException;
use Opengento\Document\Model\File\UploaderHandler;

class Upload extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Opengento_Document::document_type_save';

    /**
     * @var UploaderHandler
     */
    private $uploaderHandler;

    public function __construct(
        Context $context,
        UploaderHandler $uploaderHandler
    ) {
        $this->uploaderHandler = $uploaderHandler;
        parent::__construct($context);
    }

    public function execute()
    {
        try {
            $result = $this->upload();
        } catch (Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }

        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }

    /**
     * @return string[]
     * @throws InputException
     * @throws UploadException
     */
    private function upload(): array
    {
        $fileId = $this->getRequest()->getParam('param_name');
        if (!$fileId) {
            throw InputException::requiredField('fileId');
        }

        return $this->uploaderHandler->upload($fileId, $this->uploaderHandler->getImageAllowedExtensions());
    }
}
