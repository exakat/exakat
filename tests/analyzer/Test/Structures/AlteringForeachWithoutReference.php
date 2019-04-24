<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class AlteringForeachWithoutReference extends Analyzer {
    /* 3 methods */

    public function testStructures_AlteringForeachWithoutReference01()  { $this->generic_test('Structures_AlteringForeachWithoutReference.01'); }
    public function testStructures_AlteringForeachWithoutReference02()  { $this->generic_test('Structures_AlteringForeachWithoutReference.02'); }
    public function testStructures_AlteringForeachWithoutReference03()  { $this->generic_test('Structures/AlteringForeachWithoutReference.03'); }
}
?>