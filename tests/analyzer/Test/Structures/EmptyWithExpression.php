<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class EmptyWithExpression extends Analyzer {
    /* 2 methods */

    public function testStructures_EmptyWithExpression01()  { $this->generic_test('Structures_EmptyWithExpression.01'); }
    public function testStructures_EmptyWithExpression02()  { $this->generic_test('Structures_EmptyWithExpression.02'); }
}
?>