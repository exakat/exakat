<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class ConstWithArray extends Analyzer {
    /* 2 methods */

    public function testPhp_ConstWithArray01()  { $this->generic_test('Php/ConstWithArray.01'); }
    public function testPhp_ConstWithArray02()  { $this->generic_test('Php/ConstWithArray.02'); }
}
?>