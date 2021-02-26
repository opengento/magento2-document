# Document Module for Magento 2

[![Latest Stable Version](https://img.shields.io/packagist/v/opengento/module-document.svg?style=flat-square)](https://packagist.org/packages/opengento/module-document)
[![License: MIT](https://img.shields.io/github/license/opengento/magento2-document.svg?style=flat-square)](./LICENSE) 
[![Packagist](https://img.shields.io/packagist/dt/opengento/module-document.svg?style=flat-square)](https://packagist.org/packages/opengento/module-document/stats)
[![Packagist](https://img.shields.io/packagist/dm/opengento/module-document.svg?style=flat-square)](https://packagist.org/packages/opengento/module-document/stats)

This module aims to help merchants to manage easily their documents in Magento 2.

 - [Setup](#setup)
   - [Composer installation](#composer-installation)
   - [Setup the module](#setup-the-module)
 - [Features](#features)
 - [Settings](#settings)
 - [Documentation](#documentation)
 - [Support](#support)
 - [Authors](#authors)
 - [License](#license)

## Setup

Magento 2 Open Source or Commerce edition is required.

###  Composer installation

Run the following composer command:

```
composer require opengento/module-document
```

### Setup the module

Run the following magento command:

```
bin/magento setup:upgrade
```

**If you are in production mode, do not forget to recompile and redeploy the static resources.**

## Features

This module aims to help merchants to manage easily their documents in Magento 2.  
Documents are sorted by types and can be manipulated with ease.

- Declare new document types:
  - from the back-office
  - from xml config files

- Create new documents:
  - from the back-office
  - from command line
  - from a cron job

Documents can be uploaded with a thumbnail. The default thumbnail of the document type can be used.  
The thumbnail can be resized in order to optimize the performance.

## Documentation

### How to declare a document type from config file

Create a new file `resource_document_types.xml` in the `etc/` folder of your module:

```xml
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:opengento:document:etc/resource_document_types.xsd">
    <documentType code="cert">
        <scheduledImport>true</scheduledImport>
        <visibility>public</visibility>
        <name>Certificates</name>
        <fileSourcePath>cert/import/</fileSourcePath>
        <fileDestPath>coa/</fileDestPath>
        <subPathLength>3</subPathLength>
        <filePattern>CERT-*.[pP][dD][fF]</filePattern>
        <fileAllowedExtensions>
            <extension>pdf</extension>
        </fileAllowedExtensions>
        <defaultImageFileName>document/image/cert/thumbnail.png</defaultImageFileName>
    </documentType>
</config>
```

### How to add a new import file processor

When files are import from command line or cron jobs, you cannot set manually metadata.  
The code, name, and pivot field has to be filled and that is what the file import processor do.  
If you need to implement your own logic on how a document should be created on the import fly, check the following code:

Implement the interface `\Opengento\Document\Model\Document\ProcessorInterface`:

```php

namespace Vendor\Module\Model\Document\Processor;

use Opengento\Document\Api\Data\DocumentInterface;
use Opengento\Document\Api\Data\DocumentTypeInterface;
use Opengento\Document\Model\Document\Helper\File;
use Opengento\Document\Model\Document\Helper\Format;
use Opengento\Document\Model\Document\ProcessorInterface;
use Opengento\Document\Model\DocumentBuilder;
use function basename;
use function dirname;

final class CustomProcessor implements ProcessorInterface
{
    public const CODE = 'custom';

    /**
     * @var DocumentBuilder
     */
    private $documentBuilder;

    /**
     * @var File
     */
    private $fileHelper;

    public function __construct(
        DocumentBuilder $documentBuilder,
        File $fileHelper
    ) {
        $this->documentBuilder = $documentBuilder;
        $this->fileHelper = $fileHelper;
    }

    public function execute(DocumentTypeInterface $documentType, string $filePath): DocumentInterface
    {
        // $filePath is the path where the source file is currently saved.
        // You can change the destination path if want to.
        // Edit the file path value with $this->documentBuilder->setFilePath($newDestPath).
        // You can also rename the file with $this->documentBuilder->setFileName($newFileName)

        $destFilePath = $this->fileHelper->getFileDestPath($documentType, $filePath);
        $fileName = basename($destFilePath);

        $this->documentBuilder->setTypeId($documentType->getId());
        $this->documentBuilder->setCode(Format::formatCode($fileName));
        $this->documentBuilder->setName(Format::formatName($fileName));
        $this->documentBuilder->setFileName($fileName);
        $this->documentBuilder->setFilePath(dirname($this->fileHelper->getRelativeFilePath($destFilePath)));

        return $this->documentBuilder->create();
    }
}
```

## Support

Raise a new [request](https://github.com/opengento/magento2-document/issues) to the issue tracker.

## Authors

- **Opengento Community** - *Lead* - [![Twitter Follow](https://img.shields.io/twitter/follow/opengento.svg?style=social)](https://twitter.com/opengento)
- **Thomas Klein** - *Maintainer* - [![GitHub followers](https://img.shields.io/github/followers/thomas-kl1.svg?style=social)](https://github.com/thomas-kl1)
- **Contributors** - *Contributor* - [![GitHub contributors](https://img.shields.io/github/contributors/opengento/magento2-document.svg?style=flat-square)](https://github.com/opengento/magento2-document/graphs/contributors)

## License

This project is licensed under the MIT License - see the [LICENSE](./LICENSE) details.

***That's all folks!***
