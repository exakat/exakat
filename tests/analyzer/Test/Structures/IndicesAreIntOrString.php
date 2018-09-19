<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class IndicesAreIntOrString extends Analyzer {
    /* 6 methods */

    public function testStructures_IndicesAreIntOrString01()  { $this->generic_test('Structures_IndicesAreIntOrString.01'); }
    public function testStructures_IndicesAreIntOrString02()  { $this->generic_test('Structures/IndicesAreIntOrString.02'); }
    public function testStructures_IndicesAreIntOrString03()  { $this->generic_test('Structures/IndicesAreIntOrString.03'); }
    public function testStructures_IndicesAreIntOrString04()  { $this->generic_test('Structures/IndicesAreIntOrString.04'); }
    public function testStructures_IndicesAreIntOrString05()  { $this->generic_test('Structures/IndicesAreIntOrString.05'); }
    public function testStructures_IndicesAreIntOrString06()  { $this->generic_test('Structures/IndicesAreIntOrString.06'); }
}
?>