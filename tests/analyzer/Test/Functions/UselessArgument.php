<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UselessArgument extends Analyzer {
    /* 8 methods */

    public function testFunctions_UselessArgument01()  { $this->generic_test('Functions/UselessArgument.01'); }
    public function testFunctions_UselessArgument02()  { $this->generic_test('Functions/UselessArgument.02'); }
    public function testFunctions_UselessArgument03()  { $this->generic_test('Functions/UselessArgument.03'); }
    public function testFunctions_UselessArgument04()  { $this->generic_test('Functions/UselessArgument.04'); }
    public function testFunctions_UselessArgument05()  { $this->generic_test('Functions/UselessArgument.05'); }
    public function testFunctions_UselessArgument06()  { $this->generic_test('Functions/UselessArgument.06'); }
    public function testFunctions_UselessArgument07()  { $this->generic_test('Functions/UselessArgument.07'); }
    public function testFunctions_UselessArgument08()  { $this->generic_test('Functions/UselessArgument.08'); }
}
?>