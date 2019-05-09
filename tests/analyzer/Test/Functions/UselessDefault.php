<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UselessDefault extends Analyzer {
    /* 4 methods */

    public function testFunctions_UselessDefault01()  { $this->generic_test('Functions/UselessDefault.01'); }
    public function testFunctions_UselessDefault02()  { $this->generic_test('Functions/UselessDefault.02'); }
    public function testFunctions_UselessDefault03()  { $this->generic_test('Functions/UselessDefault.03'); }
    public function testFunctions_UselessDefault04()  { $this->generic_test('Functions/UselessDefault.04'); }
}
?>