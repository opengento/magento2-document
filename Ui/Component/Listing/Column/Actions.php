<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Ui\Component\Listing\Column;

use Magento\Framework\Phrase;
use Magento\Ui\Component\Listing\Columns\Column;
use function is_array;

class Actions extends Column
{
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (!is_array($item)) {
                    continue;
                }

                $item[$this->getData('name')] = [
                    'edit' => [
                        'href' => $this->getContext()->getUrl(
                            $this->getData('config/editUrlPath'),
                            [$this->getData('config/editParamName') => $item[$this->getData('config/indexField')]]
                        ),
                        'label' => new Phrase('Edit'),
                    ],
                    'delete' => [
                        'href' => $this->getContext()->getUrl(
                            $this->getData('config/deleteUrlPath'),
                            [$this->getData('config/deleteParamName') => $item[$this->getData('config/indexField')]]
                        ),
                        'label' => new Phrase('Delete'),
                        'confirm' => [
                            'title' => new Phrase('Delete %1', [$item['name'] ?? '']),
                            'message' => new Phrase('Are you sure you want to delete this %1 record?', [$item['name'] ?? '']),
                        ],
                        'post' => true,
                    ],
                ];
            }
        }

        return $dataSource;
    }
}
