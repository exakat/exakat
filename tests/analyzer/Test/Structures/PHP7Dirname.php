<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class PHP7Dirname extends Analyzer {
    /* 2 methods */

    public function testStructures_PHP7Dirname01()  { $this->generic_test('Structures/PHP7Dirname.01'); }
    public function testStructures_PHP7Dirname02()  { $this->generic_test('Structures/PHP7Dirname.02'); }
}
?>