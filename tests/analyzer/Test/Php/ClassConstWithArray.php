<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class ClassConstWithArray extends Analyzer {
    /* 2 methods */

    public function testPhp_ClassConstWithArray01()  { $this->generic_test('Php/ClassConstWithArray.01'); }
    public function testPhp_ClassConstWithArray02()  { $this->generic_test('Php/ClassConstWithArray.02'); }
}
?>