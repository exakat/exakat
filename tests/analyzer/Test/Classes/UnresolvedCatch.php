<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UnresolvedCatch extends Analyzer {
    /* 2 methods */

    public function testClasses_UnresolvedCatch01()  { $this->generic_test('Classes_UnresolvedCatch.01'); }
    public function testClasses_UnresolvedCatch02()  { $this->generic_test('Classes/UnresolvedCatch.02'); }
}
?>