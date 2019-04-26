<?php

namespace Test\Arrays;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class EmptySlots extends Analyzer {
    /* 3 methods */

    public function testArrays_EmptySlots01()  { $this->generic_test('Arrays/EmptySlots.01'); }
    public function testArrays_EmptySlots02()  { $this->generic_test('Arrays/EmptySlots.02'); }
    public function testArrays_EmptySlots03()  { $this->generic_test('Arrays/EmptySlots.03'); }
}
?>