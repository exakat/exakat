<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Comparison extends Tokenizeur {
    /* 2 methods */

    public function testComparison01()  { $this->generic_test('Comparison.01'); }
    public function testComparison02()  { $this->generic_test('Comparison.02'); }
}
?>