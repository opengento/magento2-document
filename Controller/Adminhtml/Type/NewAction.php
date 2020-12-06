<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Controller\Adminhtml\Type;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Forward;
use Magento\Framework\Controller\ResultFactory;

class NewAction extends Action
{
    public const ADMIN_RESOURCE = 'Opengento_Document::document_type_save';

    public function execute()
    {
        /** @var Forward $result */
        $result = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);
        $result->forward('edit');

        return $result;
    }
}
