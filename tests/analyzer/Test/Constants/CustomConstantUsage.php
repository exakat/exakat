<?php

namespace Test\Constants;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class CustomConstantUsage extends Analyzer {
    /* 2 methods */

    public function testConstants_CustomConstantUsage01()  { $this->generic_test('Constants_CustomConstantUsage.01'); }
    public function testConstants_CustomConstantUsage02()  { $this->generic_test('Constants/CustomConstantUsage.02'); }
}
?>