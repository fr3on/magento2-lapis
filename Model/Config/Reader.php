<?php
/**
 * Copyright © fr3on. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Fr3on\Lapis\Model\Config;

use Magento\Framework\Config\Reader\Filesystem;

class Reader extends Filesystem
{
    protected $idAttributes = [
        '/config/resource' => 'id',
        '/config/resource/state' => 'id',
        '/config/resource/transition' => ['from', 'to', 'via']
    ];

    public function __construct(
        \Magento\Framework\Config\FileResolverInterface $fileResolver,
        \Fr3on\Lapis\Model\Config\Converter $converter,
        \Fr3on\Lapis\Model\Config\SchemaLocator $schemaLocator,
        \Magento\Framework\Config\ValidationStateInterface $validationState,
        string $fileName = 'lapis.xml',
        array $idAttributes = [],
        string $domDocumentClass = \Magento\Framework\Config\Dom::class,
        string $defaultScope = 'global'
    ) {
        parent::__construct(
            $fileResolver,
            $converter,
            $schemaLocator,
            $validationState,
            $fileName,
            $idAttributes,
            $domDocumentClass,
            $defaultScope
        );
    }
}
