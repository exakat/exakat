<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MethodSignatureMustBeCompatible extends Analyzer {
    /* 5 methods */

    public function testClasses_MethodSignatureMustBeCompatible01()  { $this->generic_test('Classes/MethodSignatureMustBeCompatible.01'); }
    public function testClasses_MethodSignatureMustBeCompatible02()  { $this->generic_test('Classes/MethodSignatureMustBeCompatible.02'); }
    public function testClasses_MethodSignatureMustBeCompatible03()  { $this->generic_test('Classes/MethodSignatureMustBeCompatible.03'); }
    public function testClasses_MethodSignatureMustBeCompatible04()  { $this->generic_test('Classes/MethodSignatureMustBeCompatible.04'); }
    public function testClasses_MethodSignatureMustBeCompatible05()  { $this->generic_test('Classes/MethodSignatureMustBeCompatible.05'); }
}
?>