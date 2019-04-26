<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Php72Deprecation extends Analyzer {
    /* 7 methods */

    public function testPhp_Php72Deprecation01()  { $this->generic_test('Php/Php72Deprecation.01'); }
    public function testPhp_Php72Deprecation02()  { $this->generic_test('Php/Php72Deprecation.02'); }
    public function testPhp_Php72Deprecation03()  { $this->generic_test('Php/Php72Deprecation.03'); }
    public function testPhp_Php72Deprecation04()  { $this->generic_test('Php/Php72Deprecation.04'); }
    public function testPhp_Php72Deprecation05()  { $this->generic_test('Php/Php72Deprecation.05'); }
    public function testPhp_Php72Deprecation06()  { $this->generic_test('Php/Php72Deprecation.06'); }
    public function testPhp_Php72Deprecation07()  { $this->generic_test('Php/Php72Deprecation.07'); }
}
?>