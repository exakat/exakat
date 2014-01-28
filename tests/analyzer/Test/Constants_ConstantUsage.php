<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Constants_ConstantUsage extends Analyzer {
    /* 1 methods */

    public function testConstants_ConstantUsage01()  { $this->generic_test('Constants_ConstantUsage.01'); }
}
?>