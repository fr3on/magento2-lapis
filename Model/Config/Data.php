<?php
/**
 * Copyright © fr3on. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Fr3on\Lapis\Model\Config;

use Magento\Framework\Config\Data as ConfigData;

class Data extends ConfigData
{
    public function __construct(
        \Fr3on\Lapis\Model\Config\Reader $reader,
        \Magento\Framework\Config\CacheInterface $cache,
        string $cacheId = 'lapis_cache'
    ) {
        parent::__construct($reader, $cache, $cacheId);
    }
}
