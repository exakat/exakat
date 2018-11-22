<?php

namespace Test\Security;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UploadFilenameInjection extends Analyzer {
    /* 1 methods */

    public function testSecurity_UploadFilenameInjection01()  { $this->generic_test('Security/UploadFilenameInjection.01'); }
}
?>