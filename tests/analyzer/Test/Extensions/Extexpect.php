<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extexpect extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extexpect01()  { $this->generic_test('Extensions_Extexpect.01'); }
}
?>