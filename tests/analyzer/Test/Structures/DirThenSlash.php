<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class DirThenSlash extends Analyzer {
    /* 2 methods */

    public function testStructures_DirThenSlash01()  { $this->generic_test('Structures/DirThenSlash.01'); }
    public function testStructures_DirThenSlash02()  { $this->generic_test('Structures/DirThenSlash.02'); }
}
?>