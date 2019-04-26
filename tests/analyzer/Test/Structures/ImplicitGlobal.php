<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ImplicitGlobal extends Analyzer {
    /* 5 methods */

    public function testStructures_ImplicitGlobal01()  { $this->generic_test('Structures_ImplicitGlobal.01'); }
    public function testStructures_ImplicitGlobal02()  { $this->generic_test('Structures_ImplicitGlobal.02'); }
    public function testStructures_ImplicitGlobal03()  { $this->generic_test('Structures_ImplicitGlobal.03'); }
    public function testStructures_ImplicitGlobal04()  { $this->generic_test('Structures/ImplicitGlobal.04'); }
    public function testStructures_ImplicitGlobal05()  { $this->generic_test('Structures/ImplicitGlobal.05'); }
}
?>