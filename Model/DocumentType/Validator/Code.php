<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\DocumentType\Validator;

use Magento\Framework\Phrase;
use Magento\Framework\Validator\AbstractValidator;
use Opengento\Document\Api\Data\DocumentTypeInterface;
use Opengento\Document\Model\Validator\Code as CodeValidator;

final class Code extends AbstractValidator
{
    /**
     * @var CodeValidator
     */
    private $codeValidator;

    public function __construct(
        CodeValidator $codeValidator
    ) {
        $this->codeValidator = $codeValidator;
    }

    public function isValid($value): bool
    {
        $this->_clearMessages();

        if ($value instanceof DocumentTypeInterface) {
            if (!$this->codeValidator->isValid($value->getCode())) {
                $this->_addMessages($this->codeValidator->getMessages());
            }
        } else {
            $this->_addMessages([new Phrase('The entity must implements "%1".', [DocumentTypeInterface::class])]);
        }

        return !$this->hasMessages();
    }
}
