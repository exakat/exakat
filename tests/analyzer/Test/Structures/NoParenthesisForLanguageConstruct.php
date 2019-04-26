<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NoParenthesisForLanguageConstruct extends Analyzer {
    /* 1 methods */

    public function testStructures_NoParenthesisForLanguageConstruct01()  { $this->generic_test('Structures_NoParenthesisForLanguageConstruct.01'); }
}
?>