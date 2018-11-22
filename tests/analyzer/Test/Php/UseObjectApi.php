<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UseObjectApi extends Analyzer {
    /* 1 methods */

    public function testPhp_UseObjectApi01()  { $this->generic_test('Php/UseObjectApi.01'); }
}
?>