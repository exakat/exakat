<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CouldReturnVoid extends Analyzer {
    /* 1 methods */

    public function testFunctions_CouldReturnVoid01()  { $this->generic_test('Functions/CouldReturnVoid.01'); }
}
?>