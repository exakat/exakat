<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extsession extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extsession01()  { $this->generic_test('Extensions_Extsession.01'); }
}
?>