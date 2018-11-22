<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extldap extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extldap01()  { $this->generic_test('Extensions_Extldap.01'); }
}
?>