<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ExceedingTypehint extends Analyzer {
    /* 2 methods */

    public function testFunctions_ExceedingTypehint01()  { $this->generic_test('Functions/ExceedingTypehint.01'); }
    public function testFunctions_ExceedingTypehint02()  { $this->generic_test('Functions/ExceedingTypehint.02'); }
}
?>