<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\DocumentType;

use Opengento\Document\Model\Config\Source\ReadOnlyDocumentTypes;
use function array_column;
use function in_array;

final class Authorization implements AuthorizationInterface
{
    /**
     * @var ReadOnlyDocumentTypes
     */
    private $readOnlyDocTypes;

    /**
     * @var string[]|null
     */
    private $readOnly;

    public function __construct(
        ReadOnlyDocumentTypes $readOnlyDocTypes
    ) {
        $this->readOnlyDocTypes = $readOnlyDocTypes;
    }

    public function isReadonly(string $code): bool
    {
        return in_array($code, $this->resolveReadOnlyCodes(), true);
    }

    private function resolveReadOnlyCodes(): array
    {
        return $this->readOnly ?? $this->readOnly = array_column($this->readOnlyDocTypes->toOptionArray(), 'value');
    }
}
