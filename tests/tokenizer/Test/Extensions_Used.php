<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Extensions_Used extends Analyzer {
    /* 1 methods */

    public function testExtensions_Used01()  { $this->generic_test('Extensions_Used.01'); }
}
?>