<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UseCovariance extends Analyzer {
    /* 2 methods */

    public function testPhp_UseCovariance01()  { $this->generic_test('Php/UseCovariance.01'); }
    public function testPhp_UseCovariance02()  { $this->generic_test('Php/UseCovariance.02'); }
}
?>