<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Document\Filesystem;

use Opengento\Document\Api\Data\DocumentInterface;

final class UrlResolver implements UrlResolverInterface
{
    /**
     * @var UrlResolverInterface[]
     */
    private $urlResolvers;

    public function __construct(
        array $urlResolvers = []
    ) {
        $this->urlResolvers = $urlResolvers;
    }

    public function getUrl(DocumentInterface $document): ?string
    {
        $url = null;
        foreach ($this->urlResolvers as $urlResolver) {
            $url = $url ?: $urlResolver->getUrl($document);
        }

        return $url;
    }
}
