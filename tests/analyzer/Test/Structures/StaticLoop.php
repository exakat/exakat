<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class StaticLoop extends Analyzer {
    /* 7 methods */

    public function testStructures_StaticLoop01()  { $this->generic_test('Structures_StaticLoop.01'); }
    public function testStructures_StaticLoop02()  { $this->generic_test('Structures_StaticLoop.02'); }
    public function testStructures_StaticLoop03()  { $this->generic_test('Structures_StaticLoop.03'); }
    public function testStructures_StaticLoop04()  { $this->generic_test('Structures_StaticLoop.04'); }
    public function testStructures_StaticLoop05()  { $this->generic_test('Structures/StaticLoop.05'); }
    public function testStructures_StaticLoop06()  { $this->generic_test('Structures/StaticLoop.06'); }
    public function testStructures_StaticLoop07()  { $this->generic_test('Structures/StaticLoop.07'); }
}
?>