<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Extensions_Extfdf extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extfdf01()  { $this->generic_test('Extensions_Extfdf.01'); }
}
?>