<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UsedMethods extends Analyzer {
    /* 5 methods */

    public function testClasses_UsedMethods01()  { $this->generic_test('Classes_UsedMethods.01'); }
    public function testClasses_UsedMethods02()  { $this->generic_test('Classes_UsedMethods.02'); }
    public function testClasses_UsedMethods03()  { $this->generic_test('Classes_UsedMethods.03'); }
    public function testClasses_UsedMethods04()  { $this->generic_test('Classes_UsedMethods.04'); }
    public function testClasses_UsedMethods05()  { $this->generic_test('Classes/UsedMethods.05'); }
}
?>