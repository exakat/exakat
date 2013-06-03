<?php

include_once(dirname(dirname(__FILE__)).'/Autoload.php');
spl_autoload_register('Test_Autoload::autoload');

class Test_Addition extends Test_Tokenizeur {
    /* 5 methods */
    public function testAffectation1()  { $this->generic_test('Addition.01'); }
    public function testAffectation2()  { $this->generic_test('Addition.02'); }
    public function testAffectation3()  { $this->generic_test('Addition.03'); }
    public function testAffectation4()  { $this->generic_test('Addition.04'); }
    public function testAffectation5()  { $this->generic_test('Addition.05'); }
}

?>