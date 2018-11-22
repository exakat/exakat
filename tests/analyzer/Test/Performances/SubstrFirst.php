<?php

namespace Test\Performances;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class SubstrFirst extends Analyzer {
    /* 4 methods */

    public function testPerformances_SubstrFirst01()  { $this->generic_test('Performances/SubstrFirst.01'); }
    public function testPerformances_SubstrFirst02()  { $this->generic_test('Performances/SubstrFirst.02'); }
    public function testPerformances_SubstrFirst03()  { $this->generic_test('Performances/SubstrFirst.03'); }
    public function testPerformances_SubstrFirst04()  { $this->generic_test('Performances/SubstrFirst.04'); }
}
?>