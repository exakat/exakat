<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extsimplexml extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extsimplexml01()  { $this->generic_test('Extensions_Extsimplexml.01'); }
}
?>