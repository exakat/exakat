<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CoalesceEqual extends Analyzer {
    /* 1 methods */

    public function testPhp_CoalesceEqual01()  { $this->generic_test('Php/CoalesceEqual.01'); }
}
?>