<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class CouldBeElse extends Analyzer {
    /* 2 methods */

    public function testStructures_CouldBeElse01()  { $this->generic_test('Structures/CouldBeElse.01'); }
    public function testStructures_CouldBeElse02()  { $this->generic_test('Structures/CouldBeElse.02'); }
}
?>