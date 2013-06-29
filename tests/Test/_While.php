<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _While extends Tokenizeur {
    /* 1 methods */

    public function test_While01()  { $this->generic_test('_While.01'); }
}
?>