<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\File;

/**
 * @api
 */
final class Image
{
    /**
     * @var Url
     */
    private $urlHelper;

    /**
     * @var string
     */
    private $filePath;

    /**
     * @var string
     */
    private $origFilePath;

    /**
     * @var string|null
     */
    private $url;

    /**
     * @var string|null
     */
    private $origUrl;

    /**
     * @var string
     */
    private $width;

    /**
     * @var string
     */
    private $height;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string[]
     */
    private $attributes;

    public function __construct(
        Url $urlHelper,
        string $filePath,
        string $origFilePath,
        string $width,
        string $height,
        string $label,
        array $attributes = []
    ) {
        $this->urlHelper = $urlHelper;
        $this->filePath = $filePath;
        $this->origFilePath = $origFilePath;
        $this->width = $width;
        $this->height = $height;
        $this->label = $label;
        $this->attributes = $attributes;
    }

    public function getPath(): string
    {
        return $this->filePath;
    }

    public function getOrigPath(): string
    {
        return $this->origFilePath;
    }

    public function getUrl(): string
    {
        return $this->url ?? $this->url = $this->urlHelper->getUrl($this->getPath());
    }

    public function getOrigUrl(): string
    {
        return $this->origUrl ?? $this->origUrl = $this->urlHelper->getUrl($this->getOrigPath());
    }

    public function getWidth(): string
    {
        return $this->width;
    }

    public function getHeight(): string
    {
        return $this->height;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
