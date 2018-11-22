<?php

namespace Test\Performances;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UseBlindVar extends Analyzer {
    /* 2 methods */

    public function testPerformances_UseBlindVar01()  { $this->generic_test('Performances/UseBlindVar.01'); }
    public function testPerformances_UseBlindVar02()  { $this->generic_test('Performances/UseBlindVar.02'); }
}
?>