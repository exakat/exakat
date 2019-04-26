<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extcsprng extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extcsprng01()  { $this->generic_test('Extensions/Extcsprng.01'); }
}
?>