<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extsoap extends Analyzer {
    /* 2 methods */

    public function testExtensions_Extsoap01()  { $this->generic_test('Extensions_Extsoap.01'); }
    public function testExtensions_Extsoap02()  { $this->generic_test('Extensions_Extsoap.02'); }
}
?>