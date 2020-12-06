<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\File;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\File\Mime;
use Magento\Framework\Filesystem;

/**
 * @api
 */
final class Info
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var Mime
     */
    private $mime;

    public function __construct(
        Filesystem $filesystem,
        Mime $mime
    ) {
        $this->filesystem = $filesystem;
        $this->mime = $mime;
    }

    public function stat(string $file): array
    {
        return $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->stat($file);
    }

    public function getMimeType(string $file): string
    {
        return $this->mime->getMimeType($file);
    }
}
