<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MultiplyByOne extends Analyzer {
    /* 6 methods */

    public function testStructures_MultiplyByOne01()  { $this->generic_test('Structures_MultiplyByOne.01'); }
    public function testStructures_MultiplyByOne02()  { $this->generic_test('Structures_MultiplyByOne.02'); }
    public function testStructures_MultiplyByOne03()  { $this->generic_test('Structures_MultiplyByOne.03'); }
    public function testStructures_MultiplyByOne04()  { $this->generic_test('Structures_MultiplyByOne.04'); }
    public function testStructures_MultiplyByOne05()  { $this->generic_test('Structures/MultiplyByOne.05'); }
    public function testStructures_MultiplyByOne06()  { $this->generic_test('Structures/MultiplyByOne.06'); }
}
?>