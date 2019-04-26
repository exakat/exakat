<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extctype extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extctype01()  { $this->generic_test('Extensions_Extctype.01'); }
}
?>