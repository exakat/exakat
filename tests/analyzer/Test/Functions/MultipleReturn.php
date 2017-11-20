<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Functions_MultipleReturn extends Analyzer {
    /* 3 methods */

    public function testFunctions_MultipleReturn01()  { $this->generic_test('Functions_MultipleReturn.01'); }
    public function testFunctions_MultipleReturn02()  { $this->generic_test('Functions_MultipleReturn.02'); }
    public function testFunctions_MultipleReturn03()  { $this->generic_test('Functions/MultipleReturn.03'); }
}
?>