<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Reference extends Tokenizeur {
    /* 11 methods */
    
    public function testReference01()  { $this->generic_test('Reference.01'); }
    public function testReference02()  { $this->generic_test('Reference.02'); }
    public function testReference03()  { $this->generic_test('Reference.03'); }
    public function testReference04()  { $this->generic_test('Reference.04'); }

}    public function testReference05()  { $this->generic_test('Reference.05'); }
    public function testReference06()  { $this->generic_test('Reference.06'); }
    public function testReference07()  { $this->generic_test('Reference.07'); }
    public function testReference08()  { $this->generic_test('Reference.08'); }
    public function testReference09()  { $this->generic_test('Reference.09'); }
    public function testReference10()  { $this->generic_test('Reference.10'); }
    public function testReference11()  { $this->generic_test('Reference.11'); }

?>
