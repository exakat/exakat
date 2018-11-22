<?php

namespace Test\Type;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Sql extends Analyzer {
    /* 2 methods */

    public function testType_Sql01()  { $this->generic_test('Type/Sql.01'); }
    public function testType_Sql02()  { $this->generic_test('Type/Sql.02'); }
}
?>