<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class BreakOutsideLoop extends Analyzer {
    /* 4 methods */

    public function testStructures_BreakOutsideLoop01()  { $this->generic_test('Structures_BreakOutsideLoop.01'); }
    public function testStructures_BreakOutsideLoop02()  { $this->generic_test('Structures_BreakOutsideLoop.02'); }
    public function testStructures_BreakOutsideLoop03()  { $this->generic_test('Structures_BreakOutsideLoop.03'); }
    public function testStructures_BreakOutsideLoop04()  { $this->generic_test('Structures_BreakOutsideLoop.04'); }
}
?>