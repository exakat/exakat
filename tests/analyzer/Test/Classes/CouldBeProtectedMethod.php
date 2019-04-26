<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CouldBeProtectedMethod extends Analyzer {
    /* 4 methods */

    public function testClasses_CouldBeProtectedMethod01()  { $this->generic_test('Classes/CouldBeProtectedMethod.01'); }
    public function testClasses_CouldBeProtectedMethod02()  { $this->generic_test('Classes/CouldBeProtectedMethod.02'); }
    public function testClasses_CouldBeProtectedMethod03()  { $this->generic_test('Classes/CouldBeProtectedMethod.03'); }
    public function testClasses_CouldBeProtectedMethod04()  { $this->generic_test('Classes/CouldBeProtectedMethod.04'); }
}
?>