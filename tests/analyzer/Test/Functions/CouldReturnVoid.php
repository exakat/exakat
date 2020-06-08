<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CouldReturnVoid extends Analyzer {
    /* 2 methods */

    public function testFunctions_CouldReturnVoid01()  { $this->generic_test('Functions/CouldReturnVoid.01'); }
    public function testFunctions_CouldReturnVoid02()  { $this->generic_test('Functions/CouldReturnVoid.02'); }
}
?>