<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MagicMethod extends Analyzer {
    /* 2 methods */

    public function testClasses_MagicMethod01()  { $this->generic_test('Classes_MagicMethod.01'); }
    public function testClasses_MagicMethod02()  { $this->generic_test('Classes_MagicMethod.02'); }
}
?>