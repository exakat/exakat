<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CouldBeStringable extends Analyzer {
    /* 2 methods */

    public function testClasses_CouldBeStringable01()  { $this->generic_test('Classes/CouldBeStringable.01'); }
    public function testClasses_CouldBeStringable02()  { $this->generic_test('Classes/CouldBeStringable.02'); }
}
?>