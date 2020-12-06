<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Document\Model\File;

use Magento\Framework\Exception\ValidatorException;
use Magento\Framework\File\Mime;
use Magento\Framework\Phrase;
use function in_array;
use function pathinfo;
use function strtolower;
use const PATHINFO_EXTENSION;

final class Validator
{
    private const MIME_TYPES = [
        'txt'  => 'text/plain',
        'htm'  => 'text/html',
        'html' => 'text/html',
        'php'  => 'text/html',
        'css'  => 'text/css',
        'js'   => 'application/javascript',
        'json' => 'application/json',
        'xml'  => 'application/xml',
        'swf'  => 'application/x-shockwave-flash',
        'flv'  => 'video/x-flv',

        // images
        'png'  => 'image/png',
        'jpe'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg'  => 'image/jpeg',
        'gif'  => 'image/gif',
        'bmp'  => 'image/bmp',
        'ico'  => 'image/vnd.microsoft.icon',
        'tiff' => 'image/tiff',
        'tif'  => 'image/tiff',
        'svg'  => 'image/svg+xml',
        'svgz' => 'image/svg+xml',

        // archives
        'zip'  => 'application/zip',
        'rar'  => 'application/x-rar-compressed',
        'exe'  => 'application/x-msdownload',
        'msi'  => 'application/x-msdownload',
        'cab'  => 'application/vnd.ms-cab-compressed',

        // audio/video
        'mp3'  => 'audio/mpeg',
        'qt'   => 'video/quicktime',
        'mov'  => 'video/quicktime',

        // adobe
        'pdf'  => 'application/pdf',
        'psd'  => 'image/vnd.adobe.photoshop',
        'ai'   => 'application/postscript',
        'eps'  => 'application/postscript',
        'ps'   => 'application/postscript',
    ];

    /**
     * @var Mime
     */
    private $mime;

    public function __construct(
        Mime $mime
    ) {
        $this->mime = $mime;
    }

    /**
     * @param string $file
     * @param array $allowedExtensions
     * @return bool
     * @throws ValidatorException
     */
    public function validate(string $file, array $allowedExtensions): bool
    {
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions, true)) {
            throw new ValidatorException(new Phrase('The file extension "%1" is not allowed.', [$extension]));
        }
        if ($this->mime->getMimeType($file) !== (self::MIME_TYPES[$extension] ?? 'application/octet-stream')) {
            throw new ValidatorException(new Phrase('The file extension "%1" does not match its mime type.'));
        }

        return true;
    }
}
