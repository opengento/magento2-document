<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\DocumentType\Validator;

use Magento\Framework\Phrase;
use Magento\Framework\Validator\AbstractValidator;
use Magento\Framework\Validator\ValidatorInterface;
use Opengento\Document\Api\Data\DocumentTypeInterface;

final class Code extends AbstractValidator
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(
        ValidatorInterface $validator
    ) {
        $this->validator = $validator;
    }

    public function isValid($value): bool
    {
        $this->_clearMessages();

        if ($value instanceof DocumentTypeInterface) {
            if (!$this->validator->isValid($value->getCode())) {
                $this->_addMessages($this->validator->getMessages());
            }
        } else {
            $this->_addMessages([new Phrase('The entity must implements "%1".', [DocumentTypeInterface::class])]);
        }

        return !$this->hasMessages();
    }
}
