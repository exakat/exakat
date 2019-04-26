<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extmhash extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extmhash01()  { $this->generic_test('Extensions/Extmhash.01'); }
}
?>