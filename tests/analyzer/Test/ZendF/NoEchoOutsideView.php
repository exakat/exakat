<?php

namespace Test\ZendF;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class NoEchoOutsideView extends Analyzer {
    /* 1 methods */

    public function testZendF_NoEchoOutsideView01()  { $this->generic_test('ZendF/NoEchoOutsideView.01'); }
}
?>