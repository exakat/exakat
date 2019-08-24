<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ScalarAreNotArrays extends Analyzer {
    /* 3 methods */

    public function testPhp_ScalarAreNotArrays01()  { $this->generic_test('Php/ScalarAreNotArrays.01'); }
    public function testPhp_ScalarAreNotArrays02()  { $this->generic_test('Php/ScalarAreNotArrays.02'); }
    public function testPhp_ScalarAreNotArrays03()  { $this->generic_test('Php/ScalarAreNotArrays.03'); }
}
?>