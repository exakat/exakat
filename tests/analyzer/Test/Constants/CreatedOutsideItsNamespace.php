<?php

namespace Test\Constants;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class CreatedOutsideItsNamespace extends Analyzer {
    /* 3 methods */

    public function testConstants_CreatedOutsideItsNamespace01()  { $this->generic_test('Constants_CreatedOutsideItsNamespace.01'); }
    public function testConstants_CreatedOutsideItsNamespace02()  { $this->generic_test('Constants_CreatedOutsideItsNamespace.02'); }
    public function testConstants_CreatedOutsideItsNamespace03()  { $this->generic_test('Constants_CreatedOutsideItsNamespace.03'); }
}
?>