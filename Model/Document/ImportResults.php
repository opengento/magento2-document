<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Document;

use Opengento\Document\Model\Document\Import\ResultInterface;
use Opengento\Document\Model\Document\Import\ResultInterfaceFactory;

final class ImportResults implements ImportResultsInterface
{
    /**
     * @var array
     */
    private $errors;

    /**
     * @var array
     */
    private $success;

    /**
     * @var ResultInterfaceFactory
     */
    private $resultFactory;

    /**
     * @var ResultInterface|null
     */
    private $errorResult;

    /**
     * @var ResultInterface|null
     */
    private $successResult;

    public function __construct(
        array $errors,
        array $success,
        ResultInterfaceFactory $resultFactory
    ) {
        $this->errors = $errors;
        $this->success = $success;
        $this->resultFactory = $resultFactory;
    }

    public function getErrorResult(): ResultInterface
    {
        return $this->errorResult ?? $this->errorResult = $this->createResult($this->errors);
    }

    public function getSuccessResult(): ResultInterface
    {
        return $this->successResult ?? $this->successResult = $this->createResult($this->success);
    }

    private function createResult(array $messages): ResultInterface
    {
        return $this->resultFactory->create(['messages' => $messages]);
    }
}
