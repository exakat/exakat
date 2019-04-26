<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NoReferenceForStaticProperty extends Analyzer {
    /* 1 methods */

    public function testPhp_NoReferenceForStaticProperty01()  { $this->generic_test('Php/NoReferenceForStaticProperty.01'); }
}
?>