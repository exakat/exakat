<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Return extends Tokenizeur {
    /* 2 methods */

    public function test_Return01()  { $this->generic_test('_Return.01'); }
    public function test_Return02()  { $this->generic_test('_Return.02'); }

}
?>