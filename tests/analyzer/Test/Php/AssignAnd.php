<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class AssignAnd extends Analyzer {
    /* 2 methods */

    public function testPhp_AssignAnd01()  { $this->generic_test('Php/AssignAnd.01'); }
    public function testPhp_AssignAnd02()  { $this->generic_test('Php/AssignAnd.02'); }
}
?>