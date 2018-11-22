<?php

namespace Test\Constants;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class StrangeName extends Analyzer {
    /* 3 methods */

    public function testConstants_StrangeName01()  { $this->generic_test('Constants/StrangeName.01'); }
    public function testConstants_StrangeName02()  { $this->generic_test('Constants/StrangeName.02'); }
    public function testConstants_StrangeName03()  { $this->generic_test('Constants/StrangeName.03'); }
}
?>