<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class TooManyNativeCalls extends Analyzer {
    /* 2 methods */

    public function testPhp_TooManyNativeCalls01()  { $this->generic_test('Php/TooManyNativeCalls.01'); }
    public function testPhp_TooManyNativeCalls02()  { $this->generic_test('Php/TooManyNativeCalls.02'); }
}
?>