<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extsphinx extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extsphinx01()  { $this->generic_test('Extensions/Extsphinx.01'); }
}
?>