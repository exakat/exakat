<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extevent extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extevent01()  { $this->generic_test('Extensions/Extevent.01'); }
}
?>