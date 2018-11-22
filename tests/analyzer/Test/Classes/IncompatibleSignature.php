<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class IncompatibleSignature extends Analyzer {
    /* 5 methods */

    public function testClasses_IncompatibleSignature01()  { $this->generic_test('Classes/IncompatibleSignature.01'); }
    public function testClasses_IncompatibleSignature02()  { $this->generic_test('Classes/IncompatibleSignature.02'); }
    public function testClasses_IncompatibleSignature03()  { $this->generic_test('Classes/IncompatibleSignature.03'); }
    public function testClasses_IncompatibleSignature04()  { $this->generic_test('Classes/IncompatibleSignature.04'); }
    public function testClasses_IncompatibleSignature05()  { $this->generic_test('Classes/IncompatibleSignature.05'); }
}
?>