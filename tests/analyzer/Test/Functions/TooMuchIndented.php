<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class TooMuchIndented extends Analyzer {
    /* 6 methods */

    public function testFunctions_TooMuchIndented01()  { $this->generic_test('Functions/TooMuchIndented.01'); }
    public function testFunctions_TooMuchIndented02()  { $this->generic_test('Functions/TooMuchIndented.02'); }
    public function testFunctions_TooMuchIndented03()  { $this->generic_test('Functions/TooMuchIndented.03'); }
    public function testFunctions_TooMuchIndented04()  { $this->generic_test('Functions/TooMuchIndented.04'); }
    public function testFunctions_TooMuchIndented05()  { $this->generic_test('Functions/TooMuchIndented.05'); }
    public function testFunctions_TooMuchIndented06()  { $this->generic_test('Functions/TooMuchIndented.06'); }
}
?>