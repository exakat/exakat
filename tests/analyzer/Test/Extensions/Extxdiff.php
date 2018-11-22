<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extxdiff extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extxdiff01()  { $this->generic_test('Extensions/Extxdiff.01'); }
}
?>