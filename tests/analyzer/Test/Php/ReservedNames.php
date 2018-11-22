<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class ReservedNames extends Analyzer {
    /* 1 methods */

    public function testPhp_ReservedNames01()  { $this->generic_test('Php/ReservedNames.01'); }
}
?>