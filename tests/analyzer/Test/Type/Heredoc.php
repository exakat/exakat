<?php

namespace Test\Type;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Heredoc extends Analyzer {
    /* 1 methods */

    public function testType_Heredoc01()  { $this->generic_test('Type_Heredoc.01'); }
}
?>