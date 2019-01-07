<?php

namespace Test\Type;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Path extends Analyzer {
    /* 2 methods */

    public function testType_Path01()  { $this->generic_test('Type/Path.01'); }
    public function testType_Path02()  { $this->generic_test('Type/Path.02'); }
}
?>