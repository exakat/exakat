<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extereg extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extereg01()  { $this->generic_test('Extensions_Extereg.01'); }
}
?>