<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extxsl extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extxsl01()  { $this->generic_test('Extensions_Extxsl.01'); }
}
?>