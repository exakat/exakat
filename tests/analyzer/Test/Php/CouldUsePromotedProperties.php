<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CouldUsePromotedProperties extends Analyzer {
    /* 1 methods */

    public function testPhp_CouldUsePromotedProperties01()  { $this->generic_test('Php/CouldUsePromotedProperties.01'); }
}
?>