<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class ConstVisibilityUsage extends Analyzer {
    /* 1 methods */

    public function testClasses_ConstVisibilityUsage01()  { $this->generic_test('Classes/ConstVisibilityUsage.01'); }
}
?>