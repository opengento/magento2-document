<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\Document\Filesystem;

use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\AbstractModel;
use Opengento\Document\Api\Data\DocumentInterface;
use Opengento\Document\Api\DocumentTypeRepositoryInterface;
use Opengento\Document\Model\File\Image;
use Opengento\Document\Model\File\ImageBuilder as FileImageBuilder;
use Psr\Log\LoggerInterface;

/**
 * @api
 */
final class ImageBuilder
{
    /**
     * @var FileImageBuilder
     */
    private $imageBuilder;

    /**
     * @var DocumentTypeRepositoryInterface
     */
    private $docTypeRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        FileImageBuilder $imageBuilder,
        DocumentTypeRepositoryInterface $docTypeRepository,
        LoggerInterface $logger
    ) {
        $this->imageBuilder = $imageBuilder;
        $this->docTypeRepository = $docTypeRepository;
        $this->logger = $logger;
    }

    public function setDocument(DocumentInterface $document): self
    {
        $imageFileName = $document->getImageFileName();

        if (!$imageFileName) {
            if ($document instanceof AbstractModel && $document->hasData('default_image_file_name')) {
                $imageFileName = $document->getData('default_image_file_name');
            } else {
                try {
                    $imageFileName = $this->docTypeRepository->getById($document->getTypeId())->getDefaultImageFileName();
                } catch (NoSuchEntityException $e) {
                    $this->logger->error($e->getLogMessage(), $e->getTrace());
                }
            }
        }

        $this->imageBuilder->setFilePath($imageFileName);

        return $this;
    }

    public function setImageId(string $imageId): self
    {
        $this->imageBuilder->setImageId($imageId);

        return $this;
    }

    /**
     * @param int $quality
     * @return $this
     * @throws InputException
     */
    public function setQuality(int $quality): self
    {
        $this->imageBuilder->setQuality($quality);

        return $this;
    }

    /**
     * @inheridoc
     * @throws InputException
     */
    public function create(): Image
    {
        return $this->imageBuilder->create();
    }
}
