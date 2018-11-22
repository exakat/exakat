<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extsqlsrv extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extsqlsrv01()  { $this->generic_test('Extensions_Extsqlsrv.01'); }
}
?>