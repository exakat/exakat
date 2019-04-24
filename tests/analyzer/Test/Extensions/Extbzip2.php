<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extbzip2 extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extbzip201()  { $this->generic_test('Extensions_Extbzip2.01'); }
}
?>