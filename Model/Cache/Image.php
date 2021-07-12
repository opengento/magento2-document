<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Cache;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem;
use Opengento\Document\Model\Document\Filesystem\File;

/**
 * @api
 */
final class Image
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var ManagerInterface
     */
    private $eventManager;

    public function __construct(
        Filesystem $filesystem,
        ManagerInterface $eventManager
    ) {
        $this->filesystem = $filesystem;
        $this->eventManager = $eventManager;
    }

    /**
     * @throws FileSystemException
     */
    public function clear(): void
    {
        $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA)->delete(File::IMAGE_CACHE_PATH);
        $this->eventManager->dispatch('clean_document_images_cache_after');
    }
}
