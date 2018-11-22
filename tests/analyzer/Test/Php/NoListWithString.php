<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class NoListWithString extends Analyzer {
    /* 1 methods */

    public function testPhp_NoListWithString01()  { $this->generic_test('Php/NoListWithString.01'); }
}
?>