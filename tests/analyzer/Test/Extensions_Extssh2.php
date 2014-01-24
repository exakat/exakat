<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Extensions_Extssh2 extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extssh201()  { $this->generic_test('Extensions_Extssh2.01'); }
}
?>