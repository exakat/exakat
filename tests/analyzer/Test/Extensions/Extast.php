<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extast extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extast01()  { $this->generic_test('Extensions_Extast.01'); }
}
?>