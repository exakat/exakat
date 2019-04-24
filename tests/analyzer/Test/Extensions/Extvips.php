<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extvips extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extvips01()  { $this->generic_test('Extensions/Extvips.01'); }
}
?>