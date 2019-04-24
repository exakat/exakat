<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extxmlwriter extends Analyzer {
    /* 2 methods */

    public function testExtensions_Extxmlwriter01()  { $this->generic_test('Extensions_Extxmlwriter.01'); }
    public function testExtensions_Extxmlwriter02()  { $this->generic_test('Extensions_Extxmlwriter.02'); }
}
?>