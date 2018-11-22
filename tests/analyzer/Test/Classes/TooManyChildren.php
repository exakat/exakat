<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class TooManyChildren extends Analyzer {
    /* 2 methods */

    public function testClasses_TooManyChildren01()  { $this->generic_test('Classes_TooManyChildren.01'); }
    public function testClasses_TooManyChildren02()  { $this->generic_test('Classes_TooManyChildren.02'); }
}
?>