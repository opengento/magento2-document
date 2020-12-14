<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Validator;

use Magento\Framework\Translate\AdapterInterface;
use Magento\Framework\Validator\Regex;
use Magento\Framework\Validator\ValidatorInterface;

final class Code implements ValidatorInterface
{
    /**
     * @var Regex
     */
    private $regex;

    public function __construct(
        Regex $regex
    ) {
        $this->regex = $regex;
    }

    public function isValid($value): bool
    {
        return $this->regex->isValid($value);
    }

    public function getMessages(): array
    {
        return $this->regex->getMessages();
    }

    public function setTranslator($translator = null): ValidatorInterface
    {
        return $this->regex->setTranslator($translator);
    }

    public function getTranslator(): ?AdapterInterface
    {
        return $this->regex->getTranslator();
    }

    public function hasTranslator(): bool
    {
        return $this->regex->hasTranslator();
    }
}
