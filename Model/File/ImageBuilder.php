<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\File;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Filesystem;
use Magento\Framework\Image\Factory;
use Magento\Framework\View\ConfigInterface;
use Opengento\Document\Model\Document\Helper\File;
use function array_slice;
use function basename;
use function implode;
use function strtolower;
use const DIRECTORY_SEPARATOR;

final class ImageBuilder
{
    private const MEDIA_TYPE_CONFIG_NODE = 'images';

    private const DEFAULT_QUALITY = 100;

    /**
     * @var Factory
     */
    private $imageFactory;

    /**
     * @var ConfigInterface
     */
    private $viewConfig;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var ImageFactory
     */
    private $documentImageFactory;

    /**
     * @var string|null
     */
    private $filePath;

    /**
     * @var string|null
     */
    private $imageId;

    /**
     * @var int|null
     */
    private $quality;

    public function __construct(
        Factory $imageFactory,
        ConfigInterface $viewConfig,
        Filesystem $filesystem,
        ImageFactory $documentImageFactory
    ) {
        $this->imageFactory = $imageFactory;
        $this->viewConfig = $viewConfig;
        $this->filesystem = $filesystem;
        $this->documentImageFactory = $documentImageFactory;
    }

    public function setFilePath(string $filePath): self
    {
        $this->filePath = $filePath;

        return $this;
    }

    public function setImageId(string $imageId): self
    {
        $this->imageId = $imageId;

        return $this;
    }

    /**
     * @param int $quality
     * @return $this
     * @throws InputException
     */
    public function setQuality(int $quality): self
    {
        if ($quality < 0 && $quality > 100) {
            throw InputException::invalidFieldValue('quality', $quality);
        }
        $this->quality = $quality;

        return $this;
    }

    /**
     * @inheridoc
     * @throws InputException
     */
    public function create(): Image
    {
        if (!$this->filePath) {
            throw InputException::requiredField('filePath');
        }
        if (!$this->imageId) {
            throw InputException::requiredField('imageId');
        }

        return $this->createImage(
            $this->filePath,
            $this->viewConfig->getViewConfig()->getMediaAttributes(
                'Opengento_Document',
                self::MEDIA_TYPE_CONFIG_NODE,
                $this->imageId
            )
        );
    }

    private function createImage(string $filePath, array $settings): Image
    {
        $fileName = basename($filePath);
        $path = array_slice((array) mb_str_split(strtolower($fileName)), 0, 2);
        $path[] = $fileName;
        $destPath = File::IMAGE_CACHE_PATH . $this->imageId . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $path);

        $directoryRead = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
        if ($directoryRead->isFile($filePath) && !$directoryRead->isFile($destPath)) {
            $this->createCacheImage(
                $directoryRead->getAbsolutePath($filePath),
                $directoryRead->getAbsolutePath($destPath),
                $settings
            );
        }
        $this->resetArguments();

        return $this->documentImageFactory->create([
            'filePath' => $destPath,
            'origFilePath' => $filePath,
            'width' => $settings['width'] ?? '',
            'height' => $settings['height'] ?? '',
            'label' => $fileName,
            'attributes' => []
        ]);
    }

    private function createCacheImage(string $srcPath, string $destPath, array $settings): void
    {
        $image = $this->imageFactory->create($srcPath);

        $image->keepAspectRatio($settings['aspect_ratio'] ?? true);
        $image->keepFrame($settings['frame'] ?? false);
        $image->keepTransparency($settings['transparency'] ?? true);
        $image->constrainOnly($settings['constrain'] ?? true);
        $image->backgroundColor($settings['background'] ?? null);
        $image->quality($this->quality ?? self::DEFAULT_QUALITY);
        if (isset($settings['width']) || isset($settings['height'])) {
            $image->resize($settings['width'] ?? null, $settings['height'] ?? null);
        }
        $image->save($destPath);
    }

    private function resetArguments(): void
    {
        $this->filePath = null;
        $this->imageId = null;
        $this->quality = null;
    }
}
