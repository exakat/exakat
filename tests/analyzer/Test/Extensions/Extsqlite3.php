<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extsqlite3 extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extsqlite301()  { $this->generic_test('Extensions_Extsqlite3.01'); }
}
?>