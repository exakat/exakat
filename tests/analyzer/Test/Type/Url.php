<?php

namespace Test\Type;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Url extends Analyzer {
    /* 1 methods */

    public function testType_Url01()  { $this->generic_test('Type_Url.01'); }
}
?>