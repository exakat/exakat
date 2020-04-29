<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class WrongTypedPropertyInit extends Analyzer {
    /* 4 methods */

    public function testClasses_WrongTypedPropertyInit01()  { $this->generic_test('Classes/WrongTypedPropertyInit.01'); }
    public function testClasses_WrongTypedPropertyInit02()  { $this->generic_test('Classes/WrongTypedPropertyInit.02'); }
    public function testClasses_WrongTypedPropertyInit03()  { $this->generic_test('Classes/WrongTypedPropertyInit.03'); }
    public function testClasses_WrongTypedPropertyInit04()  { $this->generic_test('Classes/WrongTypedPropertyInit.04'); }
}
?>