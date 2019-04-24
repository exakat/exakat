<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extmongo extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extmongo01()  { $this->generic_test('Extensions_Extmongo.01'); }
}
?>