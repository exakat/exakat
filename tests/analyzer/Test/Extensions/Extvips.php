<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extvips extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extvips01()  { $this->generic_test('Extensions/Extvips.01'); }
}
?>