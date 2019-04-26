<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extweakref extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extweakref01()  { $this->generic_test('Extensions/Extweakref.01'); }
}
?>