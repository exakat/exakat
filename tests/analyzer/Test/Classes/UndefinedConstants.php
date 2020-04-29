<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UndefinedConstants extends Analyzer {
    /* 11 methods */

    public function testClasses_UndefinedConstants01()  { $this->generic_test('Classes_UndefinedConstants.01'); }
    public function testClasses_UndefinedConstants02()  { $this->generic_test('Classes_UndefinedConstants.02'); }
    public function testClasses_UndefinedConstants03()  { $this->generic_test('Classes_UndefinedConstants.03'); }
    public function testClasses_UndefinedConstants04()  { $this->generic_test('Classes_UndefinedConstants.04'); }
    public function testClasses_UndefinedConstants05()  { $this->generic_test('Classes/UndefinedConstants.05'); }
    public function testClasses_UndefinedConstants06()  { $this->generic_test('Classes/UndefinedConstants.06'); }
    public function testClasses_UndefinedConstants07()  { $this->generic_test('Classes/UndefinedConstants.07'); }
    public function testClasses_UndefinedConstants08()  { $this->generic_test('Classes/UndefinedConstants.08'); }
    public function testClasses_UndefinedConstants09()  { $this->generic_test('Classes/UndefinedConstants.09'); }
    public function testClasses_UndefinedConstants10()  { $this->generic_test('Classes/UndefinedConstants.10'); }
    public function testClasses_UndefinedConstants11()  { $this->generic_test('Classes/UndefinedConstants.11'); }
}
?>