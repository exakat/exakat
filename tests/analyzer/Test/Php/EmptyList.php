<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class EmptyList extends Analyzer {
    /* 1 methods */

    public function testPhp_EmptyList01()  { $this->generic_test('Php/EmptyList.01'); }
}
?>