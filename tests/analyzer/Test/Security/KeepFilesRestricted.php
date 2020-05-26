<?php

namespace Test\Security;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class KeepFilesRestricted extends Analyzer {
    /* 3 methods */

    public function testSecurity_KeepFilesRestricted01()  { $this->generic_test('Security/KeepFilesRestricted.01'); }
    public function testSecurity_KeepFilesRestricted02()  { $this->generic_test('Security/KeepFilesRestricted.02'); }
    public function testSecurity_KeepFilesRestricted03()  { $this->generic_test('Security/KeepFilesRestricted.03'); }
}
?>