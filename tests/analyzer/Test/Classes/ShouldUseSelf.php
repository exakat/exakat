<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ShouldUseSelf extends Analyzer {
    /* 4 methods */

    public function testClasses_ShouldUseSelf01()  { $this->generic_test('Classes_ShouldUseSelf.01'); }
    public function testClasses_ShouldUseSelf02()  { $this->generic_test('Classes_ShouldUseSelf.02'); }
    public function testClasses_ShouldUseSelf03()  { $this->generic_test('Classes/ShouldUseSelf.03'); }
    public function testClasses_ShouldUseSelf04()  { $this->generic_test('Classes/ShouldUseSelf.04'); }
}
?>