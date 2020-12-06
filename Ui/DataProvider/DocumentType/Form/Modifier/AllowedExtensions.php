<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Ui\DataProvider\DocumentType\Form\Modifier;

use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use function explode;

final class AllowedExtensions implements ModifierInterface
{
    public function modifyData(array $data): array
    {
        foreach ($data as &$documentType) {
            $allowedExtensions = [];
            foreach (explode(',', $documentType['file_allowed_extensions'] ?? '') as $extension) {
                $allowedExtensions[] = ['extension' => $extension];
            }
            $documentType['file_allowed_extensions'] = $allowedExtensions;
        }

        return $data;
    }

    public function modifyMeta(array $meta): array
    {
        return $meta;
    }
}
