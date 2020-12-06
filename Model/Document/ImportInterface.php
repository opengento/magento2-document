<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Document;

use Opengento\Document\Api\Data\DocumentTypeInterface;

/**
 * @api
 */
interface ImportInterface
{
    public function execute(DocumentTypeInterface $documentType): ImportResultsInterface;
}
