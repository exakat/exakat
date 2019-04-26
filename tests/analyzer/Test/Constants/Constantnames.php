<?php

namespace Test\Constants;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Constantnames extends Analyzer {
    /* 3 methods */

    public function testConstants_Constantnames01()  { $this->generic_test('Constants_Constantnames.01'); }
    public function testConstants_Constantnames02()  { $this->generic_test('Constants_Constantnames.02'); }
    public function testConstants_Constantnames03()  { $this->generic_test('Constants/Constantnames.03'); }
}
?>