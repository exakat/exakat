<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UseSetCookie extends Analyzer {
    /* 1 methods */

    public function testPhp_UseSetCookie01()  { $this->generic_test('Php/UseSetCookie.01'); }
}
?>