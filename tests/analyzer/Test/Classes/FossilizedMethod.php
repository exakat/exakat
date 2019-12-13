<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class FossilizedMethod extends Analyzer {
    /* 2 methods */

    public function testClasses_FossilizedMethod01()  { $this->generic_test('Classes/FossilizedMethod.01'); }
    public function testClasses_FossilizedMethod02()  { $this->generic_test('Classes/FossilizedMethod.02'); }
}
?>