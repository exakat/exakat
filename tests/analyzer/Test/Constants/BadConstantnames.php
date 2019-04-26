<?php

namespace Test\Constants;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class BadConstantnames extends Analyzer {
    /* 4 methods */

    public function testConstants_BadConstantnames01()  { $this->generic_test('Constants_BadConstantnames.01'); }
    public function testConstants_BadConstantnames02()  { $this->generic_test('Constants_BadConstantnames.02'); }
    public function testConstants_BadConstantnames03()  { $this->generic_test('Constants_BadConstantnames.03'); }
    public function testConstants_BadConstantnames04()  { $this->generic_test('Constants/BadConstantnames.04'); }
}
?>