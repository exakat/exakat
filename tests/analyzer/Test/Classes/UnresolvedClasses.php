<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UnresolvedClasses extends Analyzer {
    /* 13 methods */

    public function testClasses_UnresolvedClasses01()  { $this->generic_test('Classes_UnresolvedClasses.01'); }
    public function testClasses_UnresolvedClasses02()  { $this->generic_test('Classes_UnresolvedClasses.02'); }
    public function testClasses_UnresolvedClasses03()  { $this->generic_test('Classes_UnresolvedClasses.03'); }
    public function testClasses_UnresolvedClasses04()  { $this->generic_test('Classes_UnresolvedClasses.04'); }
    public function testClasses_UnresolvedClasses05()  { $this->generic_test('Classes_UnresolvedClasses.05'); }
    public function testClasses_UnresolvedClasses06()  { $this->generic_test('Classes_UnresolvedClasses.06'); }
    public function testClasses_UnresolvedClasses07()  { $this->generic_test('Classes_UnresolvedClasses.07'); }
    public function testClasses_UnresolvedClasses08()  { $this->generic_test('Classes_UnresolvedClasses.08'); }
    public function testClasses_UnresolvedClasses09()  { $this->generic_test('Classes_UnresolvedClasses.09'); }
    public function testClasses_UnresolvedClasses10()  { $this->generic_test('Classes_UnresolvedClasses.10'); }
    public function testClasses_UnresolvedClasses11()  { $this->generic_test('Classes/UnresolvedClasses.11'); }
    public function testClasses_UnresolvedClasses12()  { $this->generic_test('Classes/UnresolvedClasses.12'); }
    public function testClasses_UnresolvedClasses13()  { $this->generic_test('Classes/UnresolvedClasses.13'); }
}
?>