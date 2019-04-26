<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MismatchedDefaultArguments extends Analyzer {
    /* 4 methods */

    public function testFunctions_MismatchedDefaultArguments01()  { $this->generic_test('Functions/MismatchedDefaultArguments.01'); }
    public function testFunctions_MismatchedDefaultArguments02()  { $this->generic_test('Functions/MismatchedDefaultArguments.02'); }
    public function testFunctions_MismatchedDefaultArguments03()  { $this->generic_test('Functions/MismatchedDefaultArguments.03'); }
    public function testFunctions_MismatchedDefaultArguments04()  { $this->generic_test('Functions/MismatchedDefaultArguments.04'); }
}
?>