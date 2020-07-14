<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MismatchProperties extends Analyzer {
    /* 2 methods */

    public function testClasses_MismatchProperties01()  { $this->generic_test('Classes/MismatchProperties.01'); }
    public function testClasses_MismatchProperties02()  { $this->generic_test('Classes/MismatchProperties.02'); }
}
?>