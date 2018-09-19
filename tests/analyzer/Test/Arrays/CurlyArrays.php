<?php

namespace Test\Arrays;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class CurlyArrays extends Analyzer {
    /* 1 methods */

    public function testArrays_CurlyArrays01()  { $this->generic_test('Arrays/CurlyArrays.01'); }
}
?>