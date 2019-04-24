<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MarkCallable extends Analyzer {
    /* 7 methods */

    public function testFunctions_MarkCallable01()  { $this->generic_test('Functions_MarkCallable.01'); }
    public function testFunctions_MarkCallable02()  { $this->generic_test('Functions_MarkCallable.02'); }
    public function testFunctions_MarkCallable03()  { $this->generic_test('Functions_MarkCallable.03'); }
    public function testFunctions_MarkCallable04()  { $this->generic_test('Functions_MarkCallable.04'); }
    public function testFunctions_MarkCallable05()  { $this->generic_test('Functions_MarkCallable.05'); }
    public function testFunctions_MarkCallable06()  { $this->generic_test('Functions/MarkCallable.06'); }
    public function testFunctions_MarkCallable07()  { $this->generic_test('Functions/MarkCallable.07'); }
}
?>