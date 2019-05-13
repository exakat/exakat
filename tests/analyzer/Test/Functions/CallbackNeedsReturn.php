<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CallbackNeedsReturn extends Analyzer {
    /* 9 methods */

    public function testFunctions_CallbackNeedsReturn01()  { $this->generic_test('Functions/CallbackNeedsReturn.01'); }
    public function testFunctions_CallbackNeedsReturn02()  { $this->generic_test('Functions/CallbackNeedsReturn.02'); }
    public function testFunctions_CallbackNeedsReturn03()  { $this->generic_test('Functions/CallbackNeedsReturn.03'); }
    public function testFunctions_CallbackNeedsReturn04()  { $this->generic_test('Functions/CallbackNeedsReturn.04'); }
    public function testFunctions_CallbackNeedsReturn05()  { $this->generic_test('Functions/CallbackNeedsReturn.05'); }
    public function testFunctions_CallbackNeedsReturn06()  { $this->generic_test('Functions/CallbackNeedsReturn.06'); }
    public function testFunctions_CallbackNeedsReturn07()  { $this->generic_test('Functions/CallbackNeedsReturn.07'); }
    public function testFunctions_CallbackNeedsReturn08()  { $this->generic_test('Functions/CallbackNeedsReturn.08'); }
    public function testFunctions_CallbackNeedsReturn09()  { $this->generic_test('Functions/CallbackNeedsReturn.09'); }
}
?>