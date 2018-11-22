<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class DirectiveName extends Analyzer {
    /* 2 methods */

    public function testPhp_DirectiveName01()  { $this->generic_test('Php/DirectiveName.01'); }
    public function testPhp_DirectiveName02()  { $this->generic_test('Php/DirectiveName.02'); }
}
?>