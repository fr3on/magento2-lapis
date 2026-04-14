<?php
/**
 * Copyright © fr3on. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Fr3on\Lapis\Model\Config;

use Magento\Framework\Config\SchemaLocatorInterface;
use Magento\Framework\Module\Dir;

class SchemaLocator implements SchemaLocatorInterface
{
    private string $schema;

    public function __construct(Dir $moduleDirReader)
    {
        $etcDir = $moduleDirReader->getDir('Fr3on_Lapis', Dir::MODULE_ETC_DIR);
        $this->schema = $etcDir . DIRECTORY_SEPARATOR . 'lapis.xsd';
    }

    public function getSchema()
    {
        return $this->schema;
    }

    public function getPerFileSchema()
    {
        return null;
    }
}
