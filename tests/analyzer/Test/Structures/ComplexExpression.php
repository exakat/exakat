<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_ComplexExpression extends Analyzer {
    /* 3 methods */

    public function testStructures_ComplexExpression01()  { $this->generic_test('Structures/ComplexExpression.01'); }
    public function testStructures_ComplexExpression02()  { $this->generic_test('Structures/ComplexExpression.02'); }
    public function testStructures_ComplexExpression03()  { $this->generic_test('Structures/ComplexExpression.03'); }
}
?>