<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extwasm extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extwasm01()  { $this->generic_test('Extensions/Extwasm.01'); }
}
?>