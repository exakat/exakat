<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extnsapi extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extnsapi01()  { $this->generic_test('Extensions/Extnsapi.01'); }
}
?>