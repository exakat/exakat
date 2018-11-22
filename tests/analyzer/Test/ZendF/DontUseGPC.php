<?php

namespace Test\ZendF;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class DontUseGPC extends Analyzer {
    /* 1 methods */

    public function testZendF_DontUseGPC01()  { $this->generic_test('ZendF/DontUseGPC.01'); }
}
?>