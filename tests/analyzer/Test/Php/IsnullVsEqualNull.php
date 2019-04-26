<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class IsnullVsEqualNull extends Analyzer {
    /* 1 methods */

    public function testPhp_IsnullVsEqualNull01()  { $this->generic_test('Php/IsnullVsEqualNull.01'); }
}
?>