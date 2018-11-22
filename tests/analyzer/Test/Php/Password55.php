<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Password55 extends Analyzer {
    /* 1 methods */

    public function testPhp_Password5501()  { $this->generic_test('Php/Password55.01'); }
}
?>