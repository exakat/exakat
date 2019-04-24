<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MustReturn extends Analyzer {
    /* 2 methods */

    public function testFunctions_MustReturn01()  { $this->generic_test('Functions_MustReturn.01'); }
    public function testFunctions_MustReturn02()  { $this->generic_test('Functions_MustReturn.02'); }
}
?>