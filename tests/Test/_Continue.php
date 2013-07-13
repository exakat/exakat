<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Continue extends Tokenizeur {
    /* 1 methods */

    public function test_Continue01()  { $this->generic_test('_Continue.01'); }
}
?>