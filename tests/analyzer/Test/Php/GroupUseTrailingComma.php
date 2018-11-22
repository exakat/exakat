<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class GroupUseTrailingComma extends Analyzer {
    /* 1 methods */

    public function testPhp_GroupUseTrailingComma01()  { $this->generic_test('Php/GroupUseTrailingComma.01'); }
}
?>