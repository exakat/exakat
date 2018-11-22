<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class NoParenthesisForLanguageConstruct extends Analyzer {
    /* 1 methods */

    public function testStructures_NoParenthesisForLanguageConstruct01()  { $this->generic_test('Structures_NoParenthesisForLanguageConstruct.01'); }
}
?>