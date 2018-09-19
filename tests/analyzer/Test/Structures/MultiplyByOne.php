<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class MultiplyByOne extends Analyzer {
    /* 4 methods */

    public function testStructures_MultiplyByOne01()  { $this->generic_test('Structures_MultiplyByOne.01'); }
    public function testStructures_MultiplyByOne02()  { $this->generic_test('Structures_MultiplyByOne.02'); }
    public function testStructures_MultiplyByOne03()  { $this->generic_test('Structures_MultiplyByOne.03'); }
    public function testStructures_MultiplyByOne04()  { $this->generic_test('Structures_MultiplyByOne.04'); }
}
?>