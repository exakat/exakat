<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ParentFirst extends Analyzer {
    /* 2 methods */

    public function testClasses_ParentFirst01()  { $this->generic_test('Classes/ParentFirst.01'); }
    public function testClasses_ParentFirst02()  { $this->generic_test('Classes/ParentFirst.02'); }
}
?>