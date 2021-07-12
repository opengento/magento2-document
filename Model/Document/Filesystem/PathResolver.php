<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Document\Filesystem;

use Opengento\Document\Api\Data\DocumentTypeInterface;
use function array_column;
use function array_reduce;
use function preg_replace;
use function usort;
use const DIRECTORY_SEPARATOR;

final class PathResolver implements PathResolverInterface
{
    private const SANITIZE_REGEX = ['/[.]{2,}/', '/[\\\\]+/', '/[\/]+/'];
    private const SANITIZE_REPLACE = ['', DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR];

    /**
     * @var PathResolverInterface[]
     */
    private $resolvers;

    public function __construct(array $resolvers)
    {
        usort(
            $resolvers,
            static function (array $resolverA, array $resolverB): int {
                return ($resolverA['sortOrder'] ?? 0) <=> ($resolverB['sortOrder'] ?? 0);
            }
        );
        $this->resolvers = array_column($resolvers, 'resolver');
    }

    public function resolvePath(DocumentTypeInterface $documentType): string
    {
        return $this->sanitizePath(array_reduce(
            $this->resolvers,
            static function(string $path, PathResolverInterface $pathResolver) use ($documentType): string {
                return $path . DIRECTORY_SEPARATOR . $pathResolver->resolvePath($documentType);
            },
            ''
        ));
    }

    private function sanitizePath(string $path): string
    {
        return preg_replace(self::SANITIZE_REGEX, self::SANITIZE_REPLACE, $path);
    }
}
