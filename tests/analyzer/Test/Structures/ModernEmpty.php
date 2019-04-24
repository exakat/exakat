<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ModernEmpty extends Analyzer {
    /* 5 methods */

    public function testStructures_ModernEmpty01()  { $this->generic_test('Structures/ModernEmpty.01'); }
    public function testStructures_ModernEmpty02()  { $this->generic_test('Structures/ModernEmpty.02'); }
    public function testStructures_ModernEmpty03()  { $this->generic_test('Structures/ModernEmpty.03'); }
    public function testStructures_ModernEmpty04()  { $this->generic_test('Structures/ModernEmpty.04'); }
    public function testStructures_ModernEmpty05()  { $this->generic_test('Structures/ModernEmpty.05'); }
}
?>