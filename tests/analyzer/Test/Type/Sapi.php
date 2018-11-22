<?php

namespace Test\Type;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Sapi extends Analyzer {
    /* 1 methods */

    public function testType_Sapi01()  { $this->generic_test('Type/Sapi.01'); }
}
?>