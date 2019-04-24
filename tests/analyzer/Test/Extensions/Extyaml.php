<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extyaml extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extyaml01()  { $this->generic_test('Extensions_Extyaml.01'); }
}
?>