<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Constants_Constantnames extends Analyzer {
    /* 2 methods */

    public function testConstants_Constantnames01()  { $this->generic_test('Constants_Constantnames.01'); }
    public function testConstants_Constantnames02()  { $this->generic_test('Constants_Constantnames.02'); }
}
?>