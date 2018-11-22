<?php

namespace Test\Traits;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class LocallyUsedProperty extends Analyzer {
    /* 3 methods */

    public function testTraits_LocallyUsedProperty01()  { $this->generic_test('Traits/LocallyUsedProperty.01'); }
    public function testTraits_LocallyUsedProperty02()  { $this->generic_test('Traits/LocallyUsedProperty.02'); }
    public function testTraits_LocallyUsedProperty03()  { $this->generic_test('Traits/LocallyUsedProperty.03'); }
}
?>