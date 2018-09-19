<?php

namespace Test\Arrays;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class ArrayNSUsage extends Analyzer {
    /* 2 methods */

    public function testArrays_ArrayNSUsage01()  { $this->generic_test('Arrays/ArrayNSUsage.01'); }
    public function testArrays_ArrayNSUsage02()  { $this->generic_test('Arrays/ArrayNSUsage.02'); }
}
?>