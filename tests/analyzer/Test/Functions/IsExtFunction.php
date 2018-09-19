<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class IsExtFunction extends Analyzer {
    /* 2 methods */

    public function testFunctions_IsExtFunction01()  { $this->generic_test('Functions_IsExtFunction.01'); }
    public function testFunctions_IsExtFunction02()  { $this->generic_test('Functions_IsExtFunction.02'); }
}
?>