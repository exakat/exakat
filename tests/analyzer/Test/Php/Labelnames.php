<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Labelnames extends Analyzer {
    /* 1 methods */

    public function testPhp_Labelnames01()  { $this->generic_test('Php/Labelnames.01'); }
}
?>