<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UnusedMethods extends Analyzer {
    /* 8 methods */

    public function testClasses_UnusedMethods01()  { $this->generic_test('Classes_UnusedMethods.01'); }
    public function testClasses_UnusedMethods02()  { $this->generic_test('Classes_UnusedMethods.02'); }
    public function testClasses_UnusedMethods03()  { $this->generic_test('Classes_UnusedMethods.03'); }
    public function testClasses_UnusedMethods04()  { $this->generic_test('Classes_UnusedMethods.04'); }
    public function testClasses_UnusedMethods05()  { $this->generic_test('Classes_UnusedMethods.05'); }
    public function testClasses_UnusedMethods06()  { $this->generic_test('Classes/UnusedMethods.06'); }
    public function testClasses_UnusedMethods07()  { $this->generic_test('Classes/UnusedMethods.07'); }
}
?>