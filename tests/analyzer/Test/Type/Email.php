<?php

namespace Test\Type;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Email extends Analyzer {
    /* 1 methods */

    public function testType_Email01()  { $this->generic_test('Type_Email.01'); }
}
?>