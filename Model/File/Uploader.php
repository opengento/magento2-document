<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\File;

use DomainException;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\File\Mime;
use Magento\Framework\File\Uploader as FileUploader;
use Magento\Framework\Phrase;
use Opengento\Document\Exception\UploadException;
use function array_column;
use function array_combine;

/**
 * @api
 */
class Uploader extends FileUploader
{
    public function __construct(
        $fileId,
        Mime $fileMime,
        DirectoryList $directoryList,
        array $codeMessages
    ) {
        $codeMessages = array_combine(
            array_column($codeMessages, 'code'),
            array_column($codeMessages, 'message')
        );
        try {
            parent::__construct($fileId, $fileMime, $directoryList);
        } catch (DomainException $e) {
            throw new UploadException(
                new Phrase($codeMessages[$this->_file['error']] ?? $e->getMessage())
            );
        }
    }
}
