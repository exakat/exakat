<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ScalarTypehintUsage extends Analyzer {
    /* 8 methods */

    public function testPhp_ScalarTypehintUsage01()  { $this->generic_test('Php/ScalarTypehintUsage.01'); }
    public function testPhp_ScalarTypehintUsage02()  { $this->generic_test('Php/ScalarTypehintUsage.02'); }
    public function testPhp_ScalarTypehintUsage03()  { $this->generic_test('Php/ScalarTypehintUsage.03'); }
    public function testPhp_ScalarTypehintUsage04()  { $this->generic_test('Php/ScalarTypehintUsage.04'); }
    public function testPhp_ScalarTypehintUsage05()  { $this->generic_test('Php/ScalarTypehintUsage.05'); }
    public function testPhp_ScalarTypehintUsage06()  { $this->generic_test('Php/ScalarTypehintUsage.06'); }
    public function testPhp_ScalarTypehintUsage07()  { $this->generic_test('Php/ScalarTypehintUsage.07'); }
    public function testPhp_ScalarTypehintUsage08()  { $this->generic_test('Php/ScalarTypehintUsage.08'); }
}
?>