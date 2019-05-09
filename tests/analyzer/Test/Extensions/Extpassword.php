<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extpassword extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extpassword01()  { $this->generic_test('Extensions/Extpassword.01'); }
}
?>