<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class ListWithReference extends Analyzer {
    /* 1 methods */

    public function testPhp_ListWithReference01()  { $this->generic_test('Php/ListWithReference.01'); }
}
?>