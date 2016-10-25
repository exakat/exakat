<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Functions_WrongNumberOfArguments extends Analyzer {
    /* 4 methods */

    public function testFunctions_WrongNumberOfArguments01()  { $this->generic_test('Functions_WrongNumberOfArguments.01'); }
    public function testFunctions_WrongNumberOfArguments02()  { $this->generic_test('Functions_WrongNumberOfArguments.02'); }
    public function testFunctions_WrongNumberOfArguments03()  { $this->generic_test('Functions_WrongNumberOfArguments.03'); }
    public function testFunctions_WrongNumberOfArguments04()  { $this->generic_test('Functions/WrongNumberOfArguments.04'); }
}
?>