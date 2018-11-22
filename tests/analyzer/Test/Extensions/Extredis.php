<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extredis extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extredis01()  { $this->generic_test('Extensions_Extredis.01'); }
}
?>