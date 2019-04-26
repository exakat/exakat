<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UseObjectApi extends Analyzer {
    /* 1 methods */

    public function testPhp_UseObjectApi01()  { $this->generic_test('Php/UseObjectApi.01'); }
}
?>