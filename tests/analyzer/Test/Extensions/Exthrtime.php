<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Exthrtime extends Analyzer {
    /* 2 methods */

    public function testExtensions_Exthrtime01()  { $this->generic_test('Extensions/Exthrtime.01'); }
    public function testExtensions_Exthrtime02()  { $this->generic_test('Extensions/Exthrtime.02'); }
}
?>