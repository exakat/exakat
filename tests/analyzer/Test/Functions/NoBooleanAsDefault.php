<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NoBooleanAsDefault extends Analyzer {
    /* 2 methods */

    public function testFunctions_NoBooleanAsDefault01()  { $this->generic_test('Functions/NoBooleanAsDefault.01'); }
    public function testFunctions_NoBooleanAsDefault02()  { $this->generic_test('Functions/NoBooleanAsDefault.02'); }
}
?>