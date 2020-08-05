<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class TypehintMustBeReturned extends Analyzer {
    /* 5 methods */

    public function testFunctions_TypehintMustBeReturned01()  { $this->generic_test('Functions/TypehintMustBeReturned.01'); }
    public function testFunctions_TypehintMustBeReturned02()  { $this->generic_test('Functions/TypehintMustBeReturned.02'); }
    public function testFunctions_TypehintMustBeReturned03()  { $this->generic_test('Functions/TypehintMustBeReturned.03'); }
    public function testFunctions_TypehintMustBeReturned04()  { $this->generic_test('Functions/TypehintMustBeReturned.04'); }
    public function testFunctions_TypehintMustBeReturned05()  { $this->generic_test('Functions/TypehintMustBeReturned.05'); }
}
?>