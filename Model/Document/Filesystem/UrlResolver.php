<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Document\Filesystem;

use Opengento\Document\Api\Data\DocumentInterface;
use function array_column;
use function array_reduce;
use function usort;

final class UrlResolver implements UrlResolverInterface
{
    /**
     * @var UrlResolverInterface[]
     */
    private $resolvers;

    public function __construct(
        array $resolvers = []
    ) {
        usort(
            $resolvers,
            static function (array $resolverA, array $resolverB): int {
                return ($resolverA['sortOrder'] ?? 0) <=> ($resolverB['sortOrder'] ?? 0);
            }
        );
        $this->resolvers = array_column($resolvers, 'resolver');
    }

    public function getFileUrl(DocumentInterface $document): string
    {
        return array_reduce(
            $this->resolvers,
            static function (string $url, UrlResolverInterface $urlResolver) use ($document): string {
                return $url ?: $urlResolver->getFileUrl($document);
            },
            ''
        );
    }
}
