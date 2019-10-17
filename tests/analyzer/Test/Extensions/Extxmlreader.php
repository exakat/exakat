<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extxmlreader extends Analyzer {
    /* 3 methods */

    public function testExtensions_Extxmlreader01()  { $this->generic_test('Extensions_Extxmlreader.01'); }
    public function testExtensions_Extxmlreader02()  { $this->generic_test('Extensions/Extxmlreader.02'); }
    public function testExtensions_Extxmlreader03()  { $this->generic_test('Extensions/Extxmlreader.03'); }
}
?>