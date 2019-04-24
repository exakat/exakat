<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ClassFunctionConfusion extends Analyzer {
    /* 4 methods */

    public function testPhp_ClassFunctionConfusion01()  { $this->generic_test('Php/ClassFunctionConfusion.01'); }
    public function testPhp_ClassFunctionConfusion02()  { $this->generic_test('Php/ClassFunctionConfusion.02'); }
    public function testPhp_ClassFunctionConfusion03()  { $this->generic_test('Php/ClassFunctionConfusion.03'); }
    public function testPhp_ClassFunctionConfusion04()  { $this->generic_test('Php/ClassFunctionConfusion.04'); }
}
?>