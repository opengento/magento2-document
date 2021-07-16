<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Document\Filesystem\PathResolver;

use Opengento\Document\Api\Data\DocumentTypeInterface;
use Opengento\Document\Model\Document\Filesystem\PathResolverInterface;

final class CodeResolver implements PathResolverInterface
{
    public const DOCUMENT_FILE_PATH = 'document/file/';

    private $paths;

    public function __construct(
        array $paths = []
    ) {
        $this->paths = $paths;
    }

    public function resolvePath(DocumentTypeInterface $documentType): string
    {
        return $this->paths[$documentType->getCode()] ?? self::DOCUMENT_FILE_PATH;
    }
}
