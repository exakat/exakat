<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UndefinedConstants extends Analyzer {
    /* 8 methods */

    public function testClasses_UndefinedConstants01()  { $this->generic_test('Classes_UndefinedConstants.01'); }
    public function testClasses_UndefinedConstants02()  { $this->generic_test('Classes_UndefinedConstants.02'); }
    public function testClasses_UndefinedConstants03()  { $this->generic_test('Classes_UndefinedConstants.03'); }
    public function testClasses_UndefinedConstants04()  { $this->generic_test('Classes_UndefinedConstants.04'); }
    public function testClasses_UndefinedConstants05()  { $this->generic_test('Classes/UndefinedConstants.05'); }
    public function testClasses_UndefinedConstants06()  { $this->generic_test('Classes/UndefinedConstants.06'); }
    public function testClasses_UndefinedConstants07()  { $this->generic_test('Classes/UndefinedConstants.07'); }
    public function testClasses_UndefinedConstants08()  { $this->generic_test('Classes/UndefinedConstants.08'); }
}
?>