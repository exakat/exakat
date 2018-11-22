<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class DontReadAndWriteInOneExpression extends Analyzer {
    /* 2 methods */

    public function testStructures_DontReadAndWriteInOneExpression01()  { $this->generic_test('Structures/DontReadAndWriteInOneExpression.01'); }
    public function testStructures_DontReadAndWriteInOneExpression02()  { $this->generic_test('Structures/DontReadAndWriteInOneExpression.02'); }
}
?>