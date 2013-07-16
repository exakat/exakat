<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Reference extends Tokenizeur {
    /* 4 methods */
    
    public function testReference01()  { $this->generic_test('Reference.01'); }
    public function testReference02()  { $this->generic_test('Reference.02'); }

}    public function testReference03()  { $this->generic_test('Reference.03'); }
    public function testReference04()  { $this->generic_test('Reference.04'); }

?>
