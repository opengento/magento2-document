<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Document;

use Opengento\Document\Model\Document\Import\ResultInterface;

/**
 * @api
 */
interface ImportResultsInterface
{
    public function getErrorResult(): ResultInterface;

    public function getSuccessResult(): ResultInterface;
}
