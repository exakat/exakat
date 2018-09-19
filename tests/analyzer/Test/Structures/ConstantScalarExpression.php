<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class ConstantScalarExpression extends Analyzer {
    /* 3 methods */

    public function testStructures_ConstantScalarExpression01()  { $this->generic_test('Structures_ConstantScalarExpression.01'); }
    public function testStructures_ConstantScalarExpression02()  { $this->generic_test('Structures_ConstantScalarExpression.02'); }
    public function testStructures_ConstantScalarExpression03()  { $this->generic_test('Structures_ConstantScalarExpression.03'); }
}
?>