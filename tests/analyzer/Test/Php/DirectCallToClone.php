<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class DirectCallToClone extends Analyzer {
    /* 1 methods */

    public function testPhp_DirectCallToClone01()  { $this->generic_test('Php/DirectCallToClone.01'); }
}
?>