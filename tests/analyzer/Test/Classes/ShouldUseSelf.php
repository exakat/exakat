<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class ShouldUseSelf extends Analyzer {
    /* 2 methods */

    public function testClasses_ShouldUseSelf01()  { $this->generic_test('Classes_ShouldUseSelf.01'); }
    public function testClasses_ShouldUseSelf02()  { $this->generic_test('Classes_ShouldUseSelf.02'); }
}
?>