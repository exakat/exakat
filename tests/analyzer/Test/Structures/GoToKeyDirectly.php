<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class GoToKeyDirectly extends Analyzer {
    /* 2 methods */

    public function testStructures_GoToKeyDirectly01()  { $this->generic_test('Structures/GoToKeyDirectly.01'); }
    public function testStructures_GoToKeyDirectly02()  { $this->generic_test('Structures/GoToKeyDirectly.02'); }
}
?>