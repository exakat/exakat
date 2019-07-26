<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SerializeMagic extends Analyzer {
    /* 1 methods */

    public function testPhp_SerializeMagic01()  { $this->generic_test('Php/SerializeMagic.01'); }
}
?>