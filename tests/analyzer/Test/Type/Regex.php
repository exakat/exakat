<?php

namespace Test\Type;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Regex extends Analyzer {
    /* 1 methods */

    public function testType_Regex01()  { $this->generic_test('Type/Regex.01'); }
}
?>