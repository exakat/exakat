<?php

namespace Test\Traits;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CouldUseTrait extends Analyzer {
    /* 5 methods */

    public function testTraits_CouldUseTrait01()  { $this->generic_test('Traits/CouldUseTrait.01'); }
    public function testTraits_CouldUseTrait02()  { $this->generic_test('Traits/CouldUseTrait.02'); }
    public function testTraits_CouldUseTrait03()  { $this->generic_test('Traits/CouldUseTrait.03'); }
    public function testTraits_CouldUseTrait04()  { $this->generic_test('Traits/CouldUseTrait.04'); }
    public function testTraits_CouldUseTrait05()  { $this->generic_test('Traits/CouldUseTrait.05'); }
}
?>