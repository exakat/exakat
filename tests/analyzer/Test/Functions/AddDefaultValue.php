<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class AddDefaultValue extends Analyzer {
    /* 5 methods */

    public function testFunctions_AddDefaultValue01()  { $this->generic_test('Functions/AddDefaultValue.01'); }
    public function testFunctions_AddDefaultValue02()  { $this->generic_test('Functions/AddDefaultValue.02'); }
    public function testFunctions_AddDefaultValue03()  { $this->generic_test('Functions/AddDefaultValue.03'); }
    public function testFunctions_AddDefaultValue04()  { $this->generic_test('Functions/AddDefaultValue.04'); }
    public function testFunctions_AddDefaultValue05()  { $this->generic_test('Functions/AddDefaultValue.05'); }
}
?>