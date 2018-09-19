<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class OneVariableStrings extends Analyzer {
    /* 4 methods */

    public function testType_OneVariableStrings01()  { $this->generic_test('Type_OneVariableStrings.01'); }
    public function testType_OneVariableStrings02()  { $this->generic_test('Type_OneVariableStrings.02'); }
    public function testType_OneVariableStrings03()  { $this->generic_test('Type_OneVariableStrings.03'); }
    public function testType_OneVariableStrings04()  { $this->generic_test('Type/OneVariableStrings.04'); }
}
?>