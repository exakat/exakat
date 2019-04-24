<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Php72NewFunctions extends Analyzer {
    /* 1 methods */

    public function testPhp_Php72NewFunctions01()  { $this->generic_test('Php/Php72NewFunctions.01'); }
}
?>