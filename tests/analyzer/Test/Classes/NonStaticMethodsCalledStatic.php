<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NonStaticMethodsCalledStatic extends Analyzer {
    /* 12 methods */

    public function testClasses_NonStaticMethodsCalledStatic01()  { $this->generic_test('Classes_NonStaticMethodsCalledStatic.01'); }
    public function testClasses_NonStaticMethodsCalledStatic02()  { $this->generic_test('Classes_NonStaticMethodsCalledStatic.02'); }
    public function testClasses_NonStaticMethodsCalledStatic03()  { $this->generic_test('Classes_NonStaticMethodsCalledStatic.03'); }
    public function testClasses_NonStaticMethodsCalledStatic04()  { $this->generic_test('Classes_NonStaticMethodsCalledStatic.04'); }
    public function testClasses_NonStaticMethodsCalledStatic05()  { $this->generic_test('Classes_NonStaticMethodsCalledStatic.05'); }
    public function testClasses_NonStaticMethodsCalledStatic06()  { $this->generic_test('Classes_NonStaticMethodsCalledStatic.06'); }
    public function testClasses_NonStaticMethodsCalledStatic07()  { $this->generic_test('Classes_NonStaticMethodsCalledStatic.07'); }
    public function testClasses_NonStaticMethodsCalledStatic08()  { $this->generic_test('Classes_NonStaticMethodsCalledStatic.08'); }
    public function testClasses_NonStaticMethodsCalledStatic09()  { $this->generic_test('Classes/NonStaticMethodsCalledStatic.09'); }
    public function testClasses_NonStaticMethodsCalledStatic10()  { $this->generic_test('Classes/NonStaticMethodsCalledStatic.10'); }
    public function testClasses_NonStaticMethodsCalledStatic11()  { $this->generic_test('Classes/NonStaticMethodsCalledStatic.11'); }
    public function testClasses_NonStaticMethodsCalledStatic12()  { $this->generic_test('Classes/NonStaticMethodsCalledStatic.12'); }
}
?>