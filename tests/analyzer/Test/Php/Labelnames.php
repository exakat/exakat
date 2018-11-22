<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Labelnames extends Analyzer {
    /* 1 methods */

    public function testPhp_Labelnames01()  { $this->generic_test('Php/Labelnames.01'); }
}
?>