<?php

namespace Test\Type;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Pcre extends Analyzer {
    /* 3 methods */

    public function testType_Pcre01()  { $this->generic_test('Type_Pcre.01'); }
    public function testType_Pcre02()  { $this->generic_test('Type_Pcre.02'); }
    public function testType_Pcre03()  { $this->generic_test('Type/Pcre.03'); }
}
?>