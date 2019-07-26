<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DontBeTooManual extends Analyzer {
    /* 2 methods */

    public function testStructures_DontBeTooManual01()  { $this->generic_test('Structures/DontBeTooManual.01'); }
    public function testStructures_DontBeTooManual02()  { $this->generic_test('Structures/DontBeTooManual.02'); }
}
?>