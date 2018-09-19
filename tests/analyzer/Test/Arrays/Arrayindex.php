<?php

namespace Test\Arrays;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Arrayindex extends Analyzer {
    /* 2 methods */

    public function testArrays_Arrayindex01()  { $this->generic_test('Arrays/Arrayindex.01'); }
    public function testArrays_Arrayindex02()  { $this->generic_test('Arrays/Arrayindex.02'); }
}
?>