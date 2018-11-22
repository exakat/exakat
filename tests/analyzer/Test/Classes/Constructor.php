<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Constructor extends Analyzer {
    /* 2 methods */

    public function testClasses_Constructor01()  { $this->generic_test('Classes_Constructor.01'); }
    public function testClasses_Constructor02()  { $this->generic_test('Classes_Constructor.02'); }
}
?>