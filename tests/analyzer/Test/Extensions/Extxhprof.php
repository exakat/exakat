<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extxhprof extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extxhprof01()  { $this->generic_test('Extensions_Extxhprof.01'); }
}
?>