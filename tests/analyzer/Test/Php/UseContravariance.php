<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UseContravariance extends Analyzer {
    /* 2 methods */

    public function testPhp_UseContravariance01()  { $this->generic_test('Php/UseContravariance.01'); }
    public function testPhp_UseContravariance02()  { $this->generic_test('Php/UseContravariance.02'); }
}
?>