<?php

namespace Test\Patterns;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Factory extends Analyzer {
    /* 1 methods */

    public function testPatterns_Factory01()  { $this->generic_test('Patterns/Factory.01'); }
}
?>