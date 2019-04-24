<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class IsNotFamily extends Analyzer {
    /* 6 methods */

    public function testClasses_IsNotFamily01()  { $this->generic_test('Classes_IsNotFamily.01'); }
    public function testClasses_IsNotFamily02()  { $this->generic_test('Classes/IsNotFamily.02'); }
    public function testClasses_IsNotFamily03()  { $this->generic_test('Classes/IsNotFamily.03'); }
    public function testClasses_IsNotFamily04()  { $this->generic_test('Classes/IsNotFamily.04'); }
    public function testClasses_IsNotFamily05()  { $this->generic_test('Classes/IsNotFamily.05'); }
    public function testClasses_IsNotFamily06()  { $this->generic_test('Classes/IsNotFamily.06'); }
}
?>