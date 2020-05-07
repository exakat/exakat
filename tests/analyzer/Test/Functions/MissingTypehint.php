<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MissingTypehint extends Analyzer {
    /* 3 methods */

    public function testFunctions_MissingTypehint01()  { $this->generic_test('Functions/MissingTypehint.01'); }
    public function testFunctions_MissingTypehint02()  { $this->generic_test('Functions/MissingTypehint.02'); }
    public function testFunctions_MissingTypehint03()  { $this->generic_test('Functions/MissingTypehint.03'); }
}
?>