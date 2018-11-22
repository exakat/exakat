<?php

namespace Test\Type;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class SilentlyCastInteger extends Analyzer {
    /* 1 methods */

    public function testType_SilentlyCastInteger01()  { $this->generic_test('Type/SilentlyCastInteger.01'); }
}
?>