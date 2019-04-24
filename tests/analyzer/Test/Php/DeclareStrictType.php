<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DeclareStrictType extends Analyzer {
    /* 6 methods */

    public function testPhp_DeclareStrictType01()  { $this->generic_test('Php/DeclareStrictType.01'); }
    public function testPhp_DeclareStrictType02()  { $this->generic_test('Php/DeclareStrictType.02'); }
    public function testPhp_DeclareStrictType03()  { $this->generic_test('Php/DeclareStrictType.03'); }
    public function testPhp_DeclareStrictType04()  { $this->generic_test('Php/DeclareStrictType.04'); }
    public function testPhp_DeclareStrictType05()  { $this->generic_test('Php/DeclareStrictType.05'); }
    public function testPhp_DeclareStrictType06()  { $this->generic_test('Php/DeclareStrictType.06'); }
}
?>