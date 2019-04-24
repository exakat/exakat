<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class FinalByOcramius extends Analyzer {
    /* 6 methods */

    public function testClasses_FinalByOcramius01()  { $this->generic_test('Classes/FinalByOcramius.01'); }
    public function testClasses_FinalByOcramius02()  { $this->generic_test('Classes/FinalByOcramius.02'); }
    public function testClasses_FinalByOcramius03()  { $this->generic_test('Classes/FinalByOcramius.03'); }
    public function testClasses_FinalByOcramius04()  { $this->generic_test('Classes/FinalByOcramius.04'); }
    public function testClasses_FinalByOcramius05()  { $this->generic_test('Classes/FinalByOcramius.05'); }
    public function testClasses_FinalByOcramius06()  { $this->generic_test('Classes/FinalByOcramius.06'); }
}
?>