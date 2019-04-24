<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Url extends Analyzer {
    /* 2 methods */

    public function testType_Url01()  { $this->generic_test('Type_Url.01'); }
    public function testType_Url02()  { $this->generic_test('Type/Url.02'); }
}
?>