<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extmhash extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extmhash01()  { $this->generic_test('Extensions/Extmhash.01'); }
}
?>