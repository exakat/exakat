<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class DynamicCalls extends Analyzer {
    /* 3 methods */

    public function testStructures_DynamicCalls01()  { $this->generic_test('Structures_DynamicCalls.01'); }
    public function testStructures_DynamicCalls02()  { $this->generic_test('Structures_DynamicCalls.02'); }
    public function testStructures_DynamicCalls03()  { $this->generic_test('Structures_DynamicCalls.03'); }
}
?>