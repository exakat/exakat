<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Extensions_Extmongo extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extmongo01()  { $this->generic_test('Extensions_Extmongo.01'); }
}
?>