<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class AbstractStatic extends Analyzer {
    /* 1 methods */

    public function testClasses_AbstractStatic01()  { $this->generic_test('Classes_AbstractStatic.01'); }
}
?>