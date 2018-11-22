<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class toStringPss extends Analyzer {
    /* 2 methods */

    public function testClasses_toStringPss01()  { $this->generic_test('Classes_toStringPss.01'); }
    public function testClasses_toStringPss02()  { $this->generic_test('Classes_toStringPss.02'); }
}
?>