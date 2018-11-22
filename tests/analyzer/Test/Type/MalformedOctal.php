<?php

namespace Test\Type;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class MalformedOctal extends Analyzer {
    /* 2 methods */

    public function testType_MalformedOctal01()  { $this->generic_test('Type_MalformedOctal.01'); }
    public function testType_MalformedOctal02()  { $this->generic_test('Type_MalformedOctal.02'); }
}
?>