<?php

namespace Test\Performances;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class MakeOneCall extends Analyzer {
    /* 7 methods */

    public function testPerformances_MakeOneCall01()  { $this->generic_test('Performances/MakeOneCall.01'); }
    public function testPerformances_MakeOneCall02()  { $this->generic_test('Performances/MakeOneCall.02'); }
    public function testPerformances_MakeOneCall03()  { $this->generic_test('Performances/MakeOneCall.03'); }
    public function testPerformances_MakeOneCall04()  { $this->generic_test('Performances/MakeOneCall.04'); }
    public function testPerformances_MakeOneCall05()  { $this->generic_test('Performances/MakeOneCall.05'); }
    public function testPerformances_MakeOneCall06()  { $this->generic_test('Performances/MakeOneCall.06'); }
    public function testPerformances_MakeOneCall07()  { $this->generic_test('Performances/MakeOneCall.07'); }
}
?>