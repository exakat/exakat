<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Functions_ShouldBeTypehinted extends Analyzer {
    /* 4 methods */

    public function testFunctions_ShouldBeTypehinted01()  { $this->generic_test('Functions_ShouldBeTypehinted.01'); }
    public function testFunctions_ShouldBeTypehinted02()  { $this->generic_test('Functions_ShouldBeTypehinted.02'); }
    public function testFunctions_ShouldBeTypehinted03()  { $this->generic_test('Functions_ShouldBeTypehinted.03'); }
    public function testFunctions_ShouldBeTypehinted04()  { $this->generic_test('Functions_ShouldBeTypehinted.04'); }
}
?>