<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class EchoWithConcat extends Analyzer {
    /* 5 methods */

    public function testStructures_EchoWithConcat01()  { $this->generic_test('Structures_EchoWithConcat.01'); }
    public function testStructures_EchoWithConcat02()  { $this->generic_test('Structures_EchoWithConcat.02'); }
    public function testStructures_EchoWithConcat03()  { $this->generic_test('Structures_EchoWithConcat.03'); }
    public function testStructures_EchoWithConcat04()  { $this->generic_test('Structures/EchoWithConcat.04'); }
    public function testStructures_EchoWithConcat05()  { $this->generic_test('Structures/EchoWithConcat.05'); }
}
?>