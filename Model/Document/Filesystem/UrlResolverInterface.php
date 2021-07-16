<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Document\Filesystem;

use Opengento\Document\Api\Data\DocumentInterface;

/**
 * @api
 */
interface UrlResolverInterface
{
    public function getFileUrl(DocumentInterface $document): string;
}
