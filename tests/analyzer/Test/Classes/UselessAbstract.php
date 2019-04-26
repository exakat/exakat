<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UselessAbstract extends Analyzer {
    /* 8 methods */

    public function testClasses_UselessAbstract01()  { $this->generic_test('Classes_UselessAbstract.01'); }
    public function testClasses_UselessAbstract02()  { $this->generic_test('Classes_UselessAbstract.02'); }
    public function testClasses_UselessAbstract03()  { $this->generic_test('Classes_UselessAbstract.03'); }
    public function testClasses_UselessAbstract04()  { $this->generic_test('Classes_UselessAbstract.04'); }
    public function testClasses_UselessAbstract05()  { $this->generic_test('Classes/UselessAbstract.05'); }
    public function testClasses_UselessAbstract06()  { $this->generic_test('Classes/UselessAbstract.06'); }
    public function testClasses_UselessAbstract07()  { $this->generic_test('Classes/UselessAbstract.07'); }
    public function testClasses_UselessAbstract08()  { $this->generic_test('Classes/UselessAbstract.08'); }
}
?>