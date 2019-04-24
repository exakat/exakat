<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extxattr extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extxattr01()  { $this->generic_test('Extensions/Extxattr.01'); }
}
?>