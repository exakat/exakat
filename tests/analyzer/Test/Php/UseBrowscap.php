<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UseBrowscap extends Analyzer {
    /* 1 methods */

    public function testPhp_UseBrowscap01()  { $this->generic_test('Php/UseBrowscap.01'); }
}
?>