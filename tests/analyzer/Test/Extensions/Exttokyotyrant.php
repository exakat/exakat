<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Exttokyotyrant extends Analyzer {
    /* 1 methods */

    public function testExtensions_Exttokyotyrant01()  { $this->generic_test('Extensions/Exttokyotyrant.01'); }
}
?>