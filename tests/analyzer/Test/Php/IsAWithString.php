<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class IsAWithString extends Analyzer {
    /* 2 methods */

    public function testPhp_IsAWithString01()  { $this->generic_test('Php/IsAWithString.01'); }
    public function testPhp_IsAWithString02()  { $this->generic_test('Php/IsAWithString.02'); }
}
?>