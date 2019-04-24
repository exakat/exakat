<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Sql extends Analyzer {
    /* 4 methods */

    public function testType_Sql01()  { $this->generic_test('Type/Sql.01'); }
    public function testType_Sql02()  { $this->generic_test('Type/Sql.02'); }
    public function testType_Sql03()  { $this->generic_test('Type/Sql.03'); }
    public function testType_Sql04()  { $this->generic_test('Type/Sql.04'); }
}
?>