<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extjson extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extjson01()  { $this->generic_test('Extensions_Extjson.01'); }
}
?>