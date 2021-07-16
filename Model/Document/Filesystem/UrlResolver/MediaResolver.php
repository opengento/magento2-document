<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Document\Filesystem\UrlResolver;

use Opengento\Document\Api\Data\DocumentInterface;
use Opengento\Document\Model\Document\Filesystem\UrlResolverInterface;
use Opengento\Document\Model\File\Url as UrlHelper;

final class MediaResolver implements UrlResolverInterface
{
    /**
     * @var UrlHelper
     */
    private $urlHelper;

    public function __construct(
        UrlHelper $urlHelper
    ) {
        $this->urlHelper = $urlHelper;
    }

    public function getFileUrl(DocumentInterface $document): string
    {
        return $this->urlHelper->getUrl($document->getFilePath() . '/' . $document->getFileName());
    }
}
