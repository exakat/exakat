<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ShouldUseArrayColumn extends Analyzer {
    /* 6 methods */

    public function testPhp_ShouldUseArrayColumn01()  { $this->generic_test('Php/ShouldUseArrayColumn.01'); }
    public function testPhp_ShouldUseArrayColumn02()  { $this->generic_test('Php/ShouldUseArrayColumn.02'); }
    public function testPhp_ShouldUseArrayColumn03()  { $this->generic_test('Php/ShouldUseArrayColumn.03'); }
    public function testPhp_ShouldUseArrayColumn04()  { $this->generic_test('Php/ShouldUseArrayColumn.04'); }
    public function testPhp_ShouldUseArrayColumn05()  { $this->generic_test('Php/ShouldUseArrayColumn.05'); }
    public function testPhp_ShouldUseArrayColumn06()  { $this->generic_test('Php/ShouldUseArrayColumn.06'); }
}
?>