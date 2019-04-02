<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class WrongCase extends Analyzer {
    /* 16 methods */

    public function testClasses_WrongCase01()  { $this->generic_test('Classes_WrongCase.01'); }
    public function testClasses_WrongCase02()  { $this->generic_test('Classes_WrongCase.02'); }
    public function testClasses_WrongCase03()  { $this->generic_test('Classes_WrongCase.03'); }
    public function testClasses_WrongCase04()  { $this->generic_test('Classes_WrongCase.04'); }
    public function testClasses_WrongCase05()  { $this->generic_test('Classes_WrongCase.05'); }
    public function testClasses_WrongCase06()  { $this->generic_test('Classes_WrongCase.06'); }
    public function testClasses_WrongCase07()  { $this->generic_test('Classes_WrongCase.07'); }
    public function testClasses_WrongCase08()  { $this->generic_test('Classes_WrongCase.08'); }
    public function testClasses_WrongCase09()  { $this->generic_test('Classes_WrongCase.09'); }
    public function testClasses_WrongCase10()  { $this->generic_test('Classes_WrongCase.10'); }
    public function testClasses_WrongCase11()  { $this->generic_test('Classes/WrongCase.11'); }
    public function testClasses_WrongCase12()  { $this->generic_test('Classes/WrongCase.12'); }
    public function testClasses_WrongCase13()  { $this->generic_test('Classes/WrongCase.13'); }
    public function testClasses_WrongCase14()  { $this->generic_test('Classes/WrongCase.14'); }
    public function testClasses_WrongCase15()  { $this->generic_test('Classes/WrongCase.15'); }
    public function testClasses_WrongCase16()  { $this->generic_test('Classes/WrongCase.16'); }
}
?>