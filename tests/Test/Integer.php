<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Integer extends Tokenizeur {
    /* 1 methods */
    public function testInteger01()  { $this->generic_test('Integer.01'); }
}
?>