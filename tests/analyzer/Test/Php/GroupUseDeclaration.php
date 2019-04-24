<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class GroupUseDeclaration extends Analyzer {
    /* 2 methods */

    public function testPhp_GroupUseDeclaration01()  { $this->generic_test('Php/GroupUseDeclaration.01'); }
    public function testPhp_GroupUseDeclaration02()  { $this->generic_test('Php/GroupUseDeclaration.02'); }
}
?>