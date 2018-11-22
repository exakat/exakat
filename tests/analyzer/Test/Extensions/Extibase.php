<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extibase extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extibase01()  { $this->generic_test('Extensions/Extibase.01'); }
}
?>