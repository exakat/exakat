<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UseCookies extends Analyzer {
    /* 2 methods */

    public function testPhp_UseCookies01()  { $this->generic_test('Php/UseCookies.01'); }
    public function testPhp_UseCookies02()  { $this->generic_test('Php/UseCookies.02'); }
}
?>