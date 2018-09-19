<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class UnusedArguments extends Analyzer {
    /* 6 methods */

    public function testFunctions_UnusedArguments01()  { $this->generic_test('Functions/UnusedArguments.01'); }
    public function testFunctions_UnusedArguments02()  { $this->generic_test('Functions/UnusedArguments.02'); }
    public function testFunctions_UnusedArguments03()  { $this->generic_test('Functions/UnusedArguments.03'); }
    public function testFunctions_UnusedArguments04()  { $this->generic_test('Functions/UnusedArguments.04'); }
    public function testFunctions_UnusedArguments05()  { $this->generic_test('Functions/UnusedArguments.05'); }
    public function testFunctions_UnusedArguments06()  { $this->generic_test('Functions/UnusedArguments.06'); }
}
?>