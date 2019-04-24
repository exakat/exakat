<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NullOnNew extends Analyzer {
    /* 2 methods */

    public function testClasses_NullOnNew01()  { $this->generic_test('Classes_NullOnNew.01'); }
    public function testClasses_NullOnNew02()  { $this->generic_test('Classes/NullOnNew.02'); }
}
?>