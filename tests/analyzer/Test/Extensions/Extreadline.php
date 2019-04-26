<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extreadline extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extreadline01()  { $this->generic_test('Extensions_Extreadline.01'); }
}
?>