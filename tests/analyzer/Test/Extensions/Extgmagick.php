<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extgmagick extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extgmagick01()  { $this->generic_test('Extensions/Extgmagick.01'); }
}
?>