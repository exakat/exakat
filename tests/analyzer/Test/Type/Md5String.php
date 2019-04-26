<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Md5String extends Analyzer {
    /* 2 methods */

    public function testType_Md5String01()  { $this->generic_test('Type_Md5String.01'); }
    public function testType_Md5String02()  { $this->generic_test('Type_Md5String.02'); }
}
?>