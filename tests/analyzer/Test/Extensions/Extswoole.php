<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extswoole extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extswoole01()  { $this->generic_test('Extensions/Extswoole.01'); }
}
?>