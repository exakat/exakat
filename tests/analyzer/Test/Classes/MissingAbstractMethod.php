<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MissingAbstractMethod extends Analyzer {
    /* 2 methods */

    public function testClasses_MissingAbstractMethod01()  { $this->generic_test('Classes/MissingAbstractMethod.01'); }
    public function testClasses_MissingAbstractMethod02()  { $this->generic_test('Classes/MissingAbstractMethod.02'); }
}
?>