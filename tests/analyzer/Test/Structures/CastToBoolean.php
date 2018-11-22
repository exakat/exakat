<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class CastToBoolean extends Analyzer {
    /* 2 methods */

    public function testStructures_CastToBoolean01()  { $this->generic_test('Structures/CastToBoolean.01'); }
    public function testStructures_CastToBoolean02()  { $this->generic_test('Structures/CastToBoolean.02'); }
}
?>