<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Assumptions extends Analyzer {
    /* 3 methods */

    public function testPhp_Assumptions01()  { $this->generic_test('Php/Assumptions.01'); }
    public function testPhp_Assumptions02()  { $this->generic_test('Php/Assumptions.02'); }
    public function testPhp_Assumptions03()  { $this->generic_test('Php/Assumptions.03'); }
}
?>