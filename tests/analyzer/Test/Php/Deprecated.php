<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Deprecated extends Analyzer {
    /* 2 methods */

    public function testPhp_Deprecated01()  { $this->generic_test('Php/Deprecated.01'); }
    public function testPhp_Deprecated02()  { $this->generic_test('Php/Deprecated.02'); }
}
?>