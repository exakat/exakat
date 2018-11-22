<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class MethodSignatureMustBeCompatible extends Analyzer {
    /* 2 methods */

    public function testClasses_MethodSignatureMustBeCompatible01()  { $this->generic_test('Classes/MethodSignatureMustBeCompatible.01'); }
    public function testClasses_MethodSignatureMustBeCompatible02()  { $this->generic_test('Classes/MethodSignatureMustBeCompatible.02'); }
}
?>