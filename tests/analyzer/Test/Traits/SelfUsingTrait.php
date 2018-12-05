<?php

namespace Test\Traits;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class SelfUsingTrait extends Analyzer {
    /* 2 methods */

    public function testTraits_SelfUsingTrait01()  { $this->generic_test('Traits/SelfUsingTrait.01'); }
    public function testTraits_SelfUsingTrait02()  { $this->generic_test('Traits/SelfUsingTrait.02'); }
}
?>