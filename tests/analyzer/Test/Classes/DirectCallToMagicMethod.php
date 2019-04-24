<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DirectCallToMagicMethod extends Analyzer {
    /* 2 methods */

    public function testClasses_DirectCallToMagicMethod01()  { $this->generic_test('Classes_DirectCallToMagicMethod.01'); }
    public function testClasses_DirectCallToMagicMethod02()  { $this->generic_test('Classes_DirectCallToMagicMethod.02'); }
}
?>