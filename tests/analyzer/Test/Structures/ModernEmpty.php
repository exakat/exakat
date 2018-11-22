<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class ModernEmpty extends Analyzer {
    /* 2 methods */

    public function testStructures_ModernEmpty01()  { $this->generic_test('Structures/ModernEmpty.01'); }
    public function testStructures_ModernEmpty02()  { $this->generic_test('Structures/ModernEmpty.02'); }
}
?>