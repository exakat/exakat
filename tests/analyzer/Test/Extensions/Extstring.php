<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extstring extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extstring01()  { $this->generic_test('Extensions/Extstring.01'); }
}
?>