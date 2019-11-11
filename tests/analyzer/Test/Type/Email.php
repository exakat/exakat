<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Email extends Analyzer {
    /* 3 methods */

    public function testType_Email01()  { $this->generic_test('Type_Email.01'); }
    public function testType_Email02()  { $this->generic_test('Type/Email.02'); }
    public function testType_Email03()  { $this->generic_test('Type/Email.03'); }
}
?>