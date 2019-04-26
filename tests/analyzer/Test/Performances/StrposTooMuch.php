<?php

namespace Test\Performances;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class StrposTooMuch extends Analyzer {
    /* 2 methods */

    public function testPerformances_StrposTooMuch01()  { $this->generic_test('Performances/StrposTooMuch.01'); }
    public function testPerformances_StrposTooMuch02()  { $this->generic_test('Performances/StrposTooMuch.02'); }
}
?>