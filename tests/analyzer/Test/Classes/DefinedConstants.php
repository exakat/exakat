<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DefinedConstants extends Analyzer {
    /* 3 methods */

    public function testClasses_DefinedConstants01()  { $this->generic_test('Classes_DefinedConstants.01'); }
    public function testClasses_DefinedConstants02()  { $this->generic_test('Classes/DefinedConstants.02'); }
    public function testClasses_DefinedConstants03()  { $this->generic_test('Classes/DefinedConstants.03'); }
}
?>