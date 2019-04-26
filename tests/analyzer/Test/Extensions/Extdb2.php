<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extdb2 extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extdb201()  { $this->generic_test('Extensions/Extdb2.01'); }
}
?>