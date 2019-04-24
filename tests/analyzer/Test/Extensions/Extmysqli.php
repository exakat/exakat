<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extmysqli extends Analyzer {
    /* 2 methods */

    public function testExtensions_Extmysqli01()  { $this->generic_test('Extensions_Extmysqli.01'); }
    public function testExtensions_Extmysqli02()  { $this->generic_test('Extensions_Extmysqli.02'); }
}
?>