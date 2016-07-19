<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Functions_UnusedArguments extends Analyzer {
    /* 4 methods */

    public function testFunctions_UnusedArguments01()  { $this->generic_test('Functions/UnusedArguments.01'); }
    public function testFunctions_UnusedArguments02()  { $this->generic_test('Functions/UnusedArguments.02'); }
    public function testFunctions_UnusedArguments03()  { $this->generic_test('Functions/UnusedArguments.03'); }
    public function testFunctions_UnusedArguments04()  { $this->generic_test('Functions/UnusedArguments.04'); }
}
?>