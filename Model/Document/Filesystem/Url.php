<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Document\Filesystem;

use Opengento\Document\Api\Data\DocumentInterface;
use Opengento\Document\Model\File\Url as UrlHelper;

/**
 * @api
 */
final class Url
{
    /**
     * @var UrlHelper
     */
    private $urlHelper;

    /**
     * @var UrlResolverInterface
     */
    private $urlResolver;

    public function __construct(
        UrlHelper $urlHelper,
        UrlResolverInterface $urlResolver
    ) {
        $this->urlHelper = $urlHelper;
        $this->urlResolver = $urlResolver;
    }

    public function getFileUrl(DocumentInterface $document): string
    {
        return $this->urlResolver->getUrl($document)
            ?: $this->urlHelper->getUrl($document->getFilePath() . '/' . $document->getFileName());
    }
}
