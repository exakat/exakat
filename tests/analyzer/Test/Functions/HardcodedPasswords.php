<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Functions_HardcodedPasswords extends Analyzer {
    /* 2 methods */

    public function testFunctions_HardcodedPasswords01()  { $this->generic_test('Functions_HardcodedPasswords.01'); }
    public function testFunctions_HardcodedPasswords02()  { $this->generic_test('Functions/HardcodedPasswords.02'); }
}
?>