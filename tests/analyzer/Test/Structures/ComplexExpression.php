<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class ComplexExpression extends Analyzer {
    /* 4 methods */

    public function testStructures_ComplexExpression01()  { $this->generic_test('Structures/ComplexExpression.01'); }
    public function testStructures_ComplexExpression02()  { $this->generic_test('Structures/ComplexExpression.02'); }
    public function testStructures_ComplexExpression03()  { $this->generic_test('Structures/ComplexExpression.03'); }
    public function testStructures_ComplexExpression04()  { $this->generic_test('Structures/ComplexExpression.04'); }
}
?>