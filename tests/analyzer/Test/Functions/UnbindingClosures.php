<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UnbindingClosures extends Analyzer {
    /* 1 methods */

    public function testFunctions_UnbindingClosures01()  { $this->generic_test('Functions/UnbindingClosures.01'); }
}
?>