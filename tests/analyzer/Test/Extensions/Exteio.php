<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Exteio extends Analyzer {
    /* 1 methods */

    public function testExtensions_Exteio01()  { $this->generic_test('Extensions/Exteio.01'); }
}
?>