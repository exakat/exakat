<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class PregMatchAllFlag extends Analyzer {
    /* 1 methods */

    public function testPhp_PregMatchAllFlag01()  { $this->generic_test('Php/PregMatchAllFlag.01'); }
}
?>