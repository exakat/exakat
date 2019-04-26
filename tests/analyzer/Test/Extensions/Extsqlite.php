<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extsqlite extends Analyzer {
    /* 2 methods */

    public function testExtensions_Extsqlite01()  { $this->generic_test('Extensions_Extsqlite.01'); }
    public function testExtensions_Extsqlite02()  { $this->generic_test('Extensions_Extsqlite.02'); }
}
?>