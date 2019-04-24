<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extcurl extends Analyzer {
    /* 3 methods */

    public function testExtensions_Extcurl01()  { $this->generic_test('Extensions_Extcurl.01'); }
    public function testExtensions_Extcurl02()  { $this->generic_test('Extensions_Extcurl.02'); }
    public function testExtensions_Extcurl03()  { $this->generic_test('Extensions_Extcurl.03'); }
}
?>