<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class AmbiguousVisibilities extends Analyzer {
    /* 2 methods */

    public function testClasses_AmbiguousVisibilities01()  { $this->generic_test('Classes/AmbiguousVisibilities.01'); }
    public function testClasses_AmbiguousVisibilities02()  { $this->generic_test('Classes/AmbiguousVisibilities.02'); }
}
?>