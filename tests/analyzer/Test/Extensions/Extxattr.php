<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extxattr extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extxattr01()  { $this->generic_test('Extensions/Extxattr.01'); }
}
?>