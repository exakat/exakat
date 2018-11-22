<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UnicodeEscapeSyntax extends Analyzer {
    /* 1 methods */

    public function testPhp_UnicodeEscapeSyntax01()  { $this->generic_test('Php/UnicodeEscapeSyntax.01'); }
}
?>