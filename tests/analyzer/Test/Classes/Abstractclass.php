<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Abstractclass extends Analyzer {
    /* 2 methods */

    public function testClasses_Abstractclass01()  { $this->generic_test('Classes_Abstractclass.01'); }
    public function testClasses_Abstractclass02()  { $this->generic_test('Classes/Abstractclass.02'); }
}
?>