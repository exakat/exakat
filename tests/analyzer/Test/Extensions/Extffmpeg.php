<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extffmpeg extends Analyzer {
    /* 2 methods */

    public function testExtensions_Extffmpeg01()  { $this->generic_test('Extensions_Extffmpeg.01'); }
    public function testExtensions_Extffmpeg02()  { $this->generic_test('Extensions/Extffmpeg.02'); }
}
?>