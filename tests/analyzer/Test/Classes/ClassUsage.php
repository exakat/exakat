<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ClassUsage extends Analyzer {
    /* 5 methods */

    public function testClasses_ClassUsage01()  { $this->generic_test('Classes_ClassUsage.01'); }
    public function testClasses_ClassUsage02()  { $this->generic_test('Classes_ClassUsage.02'); }
    public function testClasses_ClassUsage03()  { $this->generic_test('Classes_ClassUsage.03'); }
    public function testClasses_ClassUsage04()  { $this->generic_test('Classes_ClassUsage.04'); }
    public function testClasses_ClassUsage05()  { $this->generic_test('Classes/ClassUsage.05'); }
}
?>