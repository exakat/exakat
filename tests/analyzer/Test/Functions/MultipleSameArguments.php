<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class MultipleSameArguments extends Analyzer {
    /* 2 methods */

    public function testFunctions_MultipleSameArguments01()  { $this->generic_test('Functions_MultipleSameArguments.01'); }
    public function testFunctions_MultipleSameArguments02()  { $this->generic_test('Functions_MultipleSameArguments.02'); }
}
?>