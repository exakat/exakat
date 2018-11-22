<?php

namespace Test\Functions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class CouldBeStaticClosure extends Analyzer {
    /* 1 methods */

    public function testFunctions_CouldBeStaticClosure01()  { $this->generic_test('Functions/CouldBeStaticClosure.01'); }
}
?>