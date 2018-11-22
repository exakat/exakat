<?php

namespace Test\Type;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UnicodeBlock extends Analyzer {
    /* 2 methods */

    public function testType_UnicodeBlock01()  { $this->generic_test('Type_UnicodeBlock.01'); }
    public function testType_UnicodeBlock02()  { $this->generic_test('Type_UnicodeBlock.02'); }
}
?>