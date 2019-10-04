<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MimeType extends Analyzer {
    /* 3 methods */

    public function testType_MimeType01()  { $this->generic_test('Type_MimeType.01'); }
    public function testType_MimeType02()  { $this->generic_test('Type/MimeType.02'); }
    public function testType_MimeType03()  { $this->generic_test('Type/MimeType.03'); }
}
?>