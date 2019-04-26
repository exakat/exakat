<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CouldTypehint extends Analyzer {
    /* 1 methods */

    public function testFunctions_CouldTypehint01()  { $this->generic_test('Functions/CouldTypehint.01'); }
}
?>