<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extjudy extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extjudy01()  { $this->generic_test('Extensions/Extjudy.01'); }
}
?>