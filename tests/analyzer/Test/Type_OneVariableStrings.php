<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Type_OneVariableStrings extends Analyzer {
    /* 3 methods */

    public function testType_OneVariableStrings01()  { $this->generic_test('Type_OneVariableStrings.01'); }
    public function testType_OneVariableStrings02()  { $this->generic_test('Type_OneVariableStrings.02'); }
    public function testType_OneVariableStrings03()  { $this->generic_test('Type_OneVariableStrings.03'); }
}
?>