<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class PHP70scalartypehints extends Analyzer {
    /* 2 methods */

    public function testPhp_PHP70scalartypehints01()  { $this->generic_test('Php/PHP70scalartypehints.01'); }
    public function testPhp_PHP70scalartypehints02()  { $this->generic_test('Php/PHP70scalartypehints.02'); }
}
?>