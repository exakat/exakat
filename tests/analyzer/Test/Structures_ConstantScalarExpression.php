<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_ConstantScalarExpression extends Analyzer {
    /* 3 methods */

    public function testStructures_ConstantScalarExpression01()  { $this->generic_test('Structures_ConstantScalarExpression.01'); }
    public function testStructures_ConstantScalarExpression02()  { $this->generic_test('Structures_ConstantScalarExpression.02'); }
    public function testStructures_ConstantScalarExpression03()  { $this->generic_test('Structures_ConstantScalarExpression.03'); }
}
?>