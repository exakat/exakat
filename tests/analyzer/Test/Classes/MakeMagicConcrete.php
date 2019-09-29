<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MakeMagicConcrete extends Analyzer {
    /* 2 methods */

    public function testClasses_MakeMagicConcrete01()  { $this->generic_test('Classes/MakeMagicConcrete.01'); }
    public function testClasses_MakeMagicConcrete02()  { $this->generic_test('Classes/MakeMagicConcrete.02'); }
}
?>