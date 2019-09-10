<?php

namespace Test\Complete;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class PhpNativeReference extends Analyzer {
    /* 1 methods */

    public function testComplete_PhpNativeReference01()  { $this->generic_test('Complete/PhpNativeReference.01'); }
}
?>