<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Extensions_Extfilter extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extfilter01()  { $this->generic_test('Extensions_Extfilter.01'); }
}
?>