<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Functions_UselessReturn extends Analyzer {
    /* 4 methods */

    public function testFunctions_UselessReturn01()  { $this->generic_test('Functions_UselessReturn.01'); }
    public function testFunctions_UselessReturn02()  { $this->generic_test('Functions_UselessReturn.02'); }
    public function testFunctions_UselessReturn03()  { $this->generic_test('Functions_UselessReturn.03'); }
    public function testFunctions_UselessReturn04()  { $this->generic_test('Functions_UselessReturn.04'); }
}
?>