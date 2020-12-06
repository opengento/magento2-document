<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Opengento\Document\Model\File\Image;
use Opengento\Document\Model\File\ImageBuilder;

class Thumbnail extends Column
{
    /**
     * @var ImageBuilder
     */
    private $imageBuilder;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        ImageBuilder $imageBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->imageBuilder = $imageBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item[$fieldName])) {
                    $image = $this->createImage($item[$fieldName]);
                    $item[$fieldName . '_src'] = $image->getUrl();
                    $item[$fieldName . '_orig_src'] = $image->getOrigUrl();
                    $item[$fieldName . '_link'] = $this->getContext()->getUrl(
                        $this->getData('config/editUrlPath'),
                        [$this->getData('config/editParamName') => $item[$this->getData('config/indexField')]]
                    );
                    $item[$fieldName . '_alt'] = $item[$this->getData('config/altField') ?: $fieldName];
                }
            }
        }

        return $dataSource;
    }

    private function createImage(string $filePath): Image
    {
        return $this->imageBuilder
            ->setFilePath($filePath)
            ->setImageId('document_listing_thumbnail')
            ->create();
    }
}
