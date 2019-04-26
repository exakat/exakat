<?php

namespace Test\Constants;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class InvalidName extends Analyzer {
    /* 4 methods */

    public function testConstants_InvalidName01()  { $this->generic_test('Constants_InvalidName.01'); }
    public function testConstants_InvalidName02()  { $this->generic_test('Constants_InvalidName.02'); }
    public function testConstants_InvalidName03()  { $this->generic_test('Constants_InvalidName.03'); }
    public function testConstants_InvalidName04()  { $this->generic_test('Constants_InvalidName.04'); }
}
?>