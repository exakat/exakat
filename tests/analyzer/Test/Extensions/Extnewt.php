<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extnewt extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extnewt01()  { $this->generic_test('Extensions/Extnewt.01'); }
}
?>