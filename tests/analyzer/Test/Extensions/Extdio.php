<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extdio extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extdio01()  { $this->generic_test('Extensions_Extdio.01'); }
}
?>