<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class AvoidReal extends Analyzer {
    /* 1 methods */

    public function testPhp_AvoidReal01()  { $this->generic_test('Php/AvoidReal.01'); }
}
?>