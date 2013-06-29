<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Reference extends Tokenizeur {
    /* 2 methods */
    
    public function testReference01()  { $this->generic_test('Reference.01'); }
    public function testReference02()  { $this->generic_test('Reference.02'); }

}
?>
