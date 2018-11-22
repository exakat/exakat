<?php

namespace Test\Security;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class MoveUploadedFile extends Analyzer {
    /* 1 methods */

    public function testSecurity_MoveUploadedFile01()  { $this->generic_test('Security/MoveUploadedFile.01'); }
}
?>