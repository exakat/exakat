<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MissingTypehint extends Analyzer {
    /* 1 methods */

    public function testFunctions_MissingTypehint01()  { $this->generic_test('Functions/MissingTypehint.01'); }
}
?>