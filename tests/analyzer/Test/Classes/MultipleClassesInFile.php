<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MultipleClassesInFile extends Analyzer {
    /* 7 methods */

    public function testClasses_MultipleClassesInFile01()  { $this->generic_test('Classes_MultipleClassesInFile.01'); }
    public function testClasses_MultipleClassesInFile02()  { $this->generic_test('Classes_MultipleClassesInFile.02'); }
    public function testClasses_MultipleClassesInFile03()  { $this->generic_test('Classes_MultipleClassesInFile.03'); }
    public function testClasses_MultipleClassesInFile04()  { $this->generic_test('Classes/MultipleClassesInFile.04'); }
    public function testClasses_MultipleClassesInFile05()  { $this->generic_test('Classes/MultipleClassesInFile.05'); }
    public function testClasses_MultipleClassesInFile06()  { $this->generic_test('Classes/MultipleClassesInFile.06'); }
    public function testClasses_MultipleClassesInFile07()  { $this->generic_test('Classes/MultipleClassesInFile.07'); }
}
?>