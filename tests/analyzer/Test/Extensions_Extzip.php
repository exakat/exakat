<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Extensions_Extzip extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extzip01()  { $this->generic_test('Extensions_Extzip.01'); }
}
?>