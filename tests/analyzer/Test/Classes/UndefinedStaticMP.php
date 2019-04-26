<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UndefinedStaticMP extends Analyzer {
    /* 5 methods */

    public function testClasses_UndefinedStaticMP01()  { $this->generic_test('Classes_UndefinedStaticMP.01'); }
    public function testClasses_UndefinedStaticMP02()  { $this->generic_test('Classes_UndefinedStaticMP.02'); }
    public function testClasses_UndefinedStaticMP03()  { $this->generic_test('Classes/UndefinedStaticMP.03'); }
    public function testClasses_UndefinedStaticMP04()  { $this->generic_test('Classes/UndefinedStaticMP.04'); }
    public function testClasses_UndefinedStaticMP05()  { $this->generic_test('Classes/UndefinedStaticMP.05'); }
}
?>