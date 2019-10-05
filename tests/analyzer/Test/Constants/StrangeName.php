<?php

namespace Test\Constants;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class StrangeName extends Analyzer {
    /* 4 methods */

    public function testConstants_StrangeName01()  { $this->generic_test('Constants/StrangeName.01'); }
    public function testConstants_StrangeName02()  { $this->generic_test('Constants/StrangeName.02'); }
    public function testConstants_StrangeName03()  { $this->generic_test('Constants/StrangeName.03'); }
    public function testConstants_StrangeName04()  { $this->generic_test('Constants/StrangeName.04'); }
}
?>