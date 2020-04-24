<?php

namespace Test\Complete;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class PropagateConstants extends Analyzer {
    /* 5 methods */

    public function testComplete_PropagateConstants01()  { $this->generic_test('Complete/PropagateConstants.01'); }
    public function testComplete_PropagateConstants02()  { $this->generic_test('Complete/PropagateConstants.02'); }
    public function testComplete_PropagateConstants03()  { $this->generic_test('Complete/PropagateConstants.03'); }
    public function testComplete_PropagateConstants04()  { $this->generic_test('Complete/PropagateConstants.04'); }
    public function testComplete_PropagateConstants05()  { $this->generic_test('Complete/PropagateConstants.05'); }
}
?>