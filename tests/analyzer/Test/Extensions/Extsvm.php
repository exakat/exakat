<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extsvm extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extsvm01()  { $this->generic_test('Extensions/Extsvm.01'); }
}
?>