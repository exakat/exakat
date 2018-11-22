<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class ConstantClass extends Analyzer {
    /* 1 methods */

    public function testClasses_ConstantClass01()  { $this->generic_test('Classes_ConstantClass.01'); }
}
?>