<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DontUseVoid extends Analyzer {
    /* 1 methods */

    public function testFunctions_DontUseVoid01()  { $this->generic_test('Functions/DontUseVoid.01'); }
}
?>