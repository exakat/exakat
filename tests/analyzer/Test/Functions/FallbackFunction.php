<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class FallbackFunction extends Analyzer {
    /* 2 methods */

    public function testFunctions_FallbackFunction01()  { $this->generic_test('Functions/FallbackFunction.01'); }
    public function testFunctions_FallbackFunction02()  { $this->generic_test('Functions/FallbackFunction.02'); }
}
?>