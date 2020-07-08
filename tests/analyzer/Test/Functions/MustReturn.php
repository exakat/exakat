<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MustReturn extends Analyzer {
    /* 6 methods */

    public function testFunctions_MustReturn01()  { $this->generic_test('Functions_MustReturn.01'); }
    public function testFunctions_MustReturn02()  { $this->generic_test('Functions_MustReturn.02'); }
    public function testFunctions_MustReturn03()  { $this->generic_test('Functions/MustReturn.03'); }
    public function testFunctions_MustReturn04()  { $this->generic_test('Functions/MustReturn.04'); }
    public function testFunctions_MustReturn05()  { $this->generic_test('Functions/MustReturn.05'); }
    public function testFunctions_MustReturn06()  { $this->generic_test('Functions/MustReturn.06'); }
}
?>