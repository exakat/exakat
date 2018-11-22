<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UseBrowscap extends Analyzer {
    /* 1 methods */

    public function testPhp_UseBrowscap01()  { $this->generic_test('Php/UseBrowscap.01'); }
}
?>