<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extmbstring extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extmbstring01()  { $this->generic_test('Extensions_Extmbstring.01'); }
}
?>