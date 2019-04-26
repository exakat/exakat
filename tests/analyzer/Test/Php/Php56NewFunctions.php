<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Php56NewFunctions extends Analyzer {
    /* 2 methods */

    public function testPhp_Php56NewFunctions01()  { $this->generic_test('Php/Php56NewFunctions.01'); }
    public function testPhp_Php56NewFunctions02()  { $this->generic_test('Php/Php56NewFunctions.02'); }
}
?>