<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UnknownPcre2Option extends Analyzer {
    /* 3 methods */

    public function testPhp_UnknownPcre2Option01()  { $this->generic_test('Php/UnknownPcre2Option.01'); }
    public function testPhp_UnknownPcre2Option02()  { $this->generic_test('Php/UnknownPcre2Option.02'); }
    public function testPhp_UnknownPcre2Option03()  { $this->generic_test('Php/UnknownPcre2Option.03'); }
}
?>