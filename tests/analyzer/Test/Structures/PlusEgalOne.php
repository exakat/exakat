<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class PlusEgalOne extends Analyzer {
    /* 3 methods */

    public function testStructures_PlusEgalOne01()  { $this->generic_test('Structures_PlusEgalOne.01'); }
    public function testStructures_PlusEgalOne02()  { $this->generic_test('Structures_PlusEgalOne.02'); }
    public function testStructures_PlusEgalOne03()  { $this->generic_test('Structures_PlusEgalOne.03'); }
}
?>