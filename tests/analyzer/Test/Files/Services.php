<?php

namespace Test\Files;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Services extends Analyzer {
    /* 1 methods */

    public function testFiles_Services01()  { $this->generic_test('Files/Services.01'); }
}
?>