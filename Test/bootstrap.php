<?php
/**
 * Copyright © fr3on. All rights reserved.
 * See COPYING.txt for license details.
 */

// Mocking Magento Framework Classes and Interfaces for Unit Testing
if (!class_exists('Magento\Framework\Component\ComponentRegistrar')) {
    eval('namespace Magento\Framework\Component; class ComponentRegistrar { const MODULE = "module"; public static function register($type, $name, $dir) {} }');
}

// Ensure the autoloader is available
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

// Mocking the missing Factory classes that Magento usually generates
if (!class_exists('Fr3on\Lapis\Model\Data\StateFactory')) {
    eval('namespace Fr3on\Lapis\Model\Data; class StateFactory { public function create($data = []) {} }');
}

if (!class_exists('Fr3on\Lapis\Model\Data\TransitionFactory')) {
    eval('namespace Fr3on\Lapis\Model\Data; class TransitionFactory { public function create($data = []) {} }');
}

// Mocking shared Magento dependencies
if (!class_exists('Magento\Framework\App\ResourceConnection')) {
    eval('namespace Magento\Framework\App; class ResourceConnection { public function getConnection() {} public function getTableName($name) { return $name; } }');
}
