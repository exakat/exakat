<?php

namespace Test\Performances;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class StrposTooMuch extends Analyzer {
    /* 1 methods */

    public function testPerformances_StrposTooMuch01()  { $this->generic_test('Performances/StrposTooMuch.01'); }
}
?>