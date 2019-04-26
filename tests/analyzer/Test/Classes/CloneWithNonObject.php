<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CloneWithNonObject extends Analyzer {
    /* 2 methods */

    public function testClasses_CloneWithNonObject01()  { $this->generic_test('Classes/CloneWithNonObject.01'); }
    public function testClasses_CloneWithNonObject02()  { $this->generic_test('Classes/CloneWithNonObject.02'); }
}
?>