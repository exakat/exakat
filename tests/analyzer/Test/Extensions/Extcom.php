<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extcom extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extcom01()  { $this->generic_test('Extensions/Extcom.01'); }
}
?>