<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extswoole extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extswoole01()  { $this->generic_test('Extensions/Extswoole.01'); }
}
?>