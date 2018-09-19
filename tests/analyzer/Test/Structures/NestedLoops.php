<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class NestedLoops extends Analyzer {
    /* 4 methods */

    public function testStructures_NestedLoops01()  { $this->generic_test('Structures_NestedLoops.01'); }
    public function testStructures_NestedLoops02()  { $this->generic_test('Structures_NestedLoops.02'); }
    public function testStructures_NestedLoops03()  { $this->generic_test('Structures_NestedLoops.03'); }
    public function testStructures_NestedLoops04()  { $this->generic_test('Structures_NestedLoops.04'); }
}
?>