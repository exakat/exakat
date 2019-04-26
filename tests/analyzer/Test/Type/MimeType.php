<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MimeType extends Analyzer {
    /* 1 methods */

    public function testType_MimeType01()  { $this->generic_test('Type_MimeType.01'); }
}
?>