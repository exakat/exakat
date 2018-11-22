<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class MethodIsOverwritten extends Analyzer {
    /* 2 methods */

    public function testClasses_MethodIsOverwritten01()  { $this->generic_test('Classes/MethodIsOverwritten.01'); }
    public function testClasses_MethodIsOverwritten02()  { $this->generic_test('Classes/MethodIsOverwritten.02'); }
}
?>