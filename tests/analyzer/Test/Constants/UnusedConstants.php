<?php

namespace Test\Constants;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UnusedConstants extends Analyzer {
    /* 8 methods */

    public function testConstants_UnusedConstants01()  { $this->generic_test('Constants_UnusedConstants.01'); }
    public function testConstants_UnusedConstants02()  { $this->generic_test('Constants_UnusedConstants.02'); }
    public function testConstants_UnusedConstants03()  { $this->generic_test('Constants/UnusedConstants.03'); }
    public function testConstants_UnusedConstants04()  { $this->generic_test('Constants/UnusedConstants.04'); }
    public function testConstants_UnusedConstants05()  { $this->generic_test('Constants/UnusedConstants.05'); }
    public function testConstants_UnusedConstants06()  { $this->generic_test('Constants/UnusedConstants.06'); }
    public function testConstants_UnusedConstants07()  { $this->generic_test('Constants/UnusedConstants.07'); }
    public function testConstants_UnusedConstants08()  { $this->generic_test('Constants/UnusedConstants.08'); }
}
?>