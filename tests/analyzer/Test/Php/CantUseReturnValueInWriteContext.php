<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class CantUseReturnValueInWriteContext extends Analyzer {
    /* 1 methods */

    public function testPhp_CantUseReturnValueInWriteContext01()  { $this->generic_test('Php/CantUseReturnValueInWriteContext.01'); }
}
?>