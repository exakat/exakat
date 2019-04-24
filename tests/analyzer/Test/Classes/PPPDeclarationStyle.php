<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class PPPDeclarationStyle extends Analyzer {
    /* 3 methods */

    public function testClasses_PPPDeclarationStyle01()  { $this->generic_test('Classes/PPPDeclarationStyle.01'); }
    public function testClasses_PPPDeclarationStyle02()  { $this->generic_test('Classes/PPPDeclarationStyle.02'); }
    public function testClasses_PPPDeclarationStyle03()  { $this->generic_test('Classes/PPPDeclarationStyle.03'); }
}
?>