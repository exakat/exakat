<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CatchShadowsVariable extends Analyzer {
    /* 5 methods */

    public function testStructures_CatchShadowsVariable01()  { $this->generic_test('Structures_CatchShadowsVariable.01'); }
    public function testStructures_CatchShadowsVariable02()  { $this->generic_test('Structures_CatchShadowsVariable.02'); }
    public function testStructures_CatchShadowsVariable03()  { $this->generic_test('Structures_CatchShadowsVariable.03'); }
    public function testStructures_CatchShadowsVariable04()  { $this->generic_test('Structures/CatchShadowsVariable.04'); }
    public function testStructures_CatchShadowsVariable05()  { $this->generic_test('Structures/CatchShadowsVariable.05'); }
}
?>