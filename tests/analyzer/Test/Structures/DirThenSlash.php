<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DirThenSlash extends Analyzer {
    /* 3 methods */

    public function testStructures_DirThenSlash01()  { $this->generic_test('Structures/DirThenSlash.01'); }
    public function testStructures_DirThenSlash02()  { $this->generic_test('Structures/DirThenSlash.02'); }
    public function testStructures_DirThenSlash03()  { $this->generic_test('Structures/DirThenSlash.03'); }
}
?>