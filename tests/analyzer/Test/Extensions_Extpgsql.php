<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Extensions_Extpgsql extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extpgsql01()  { $this->generic_test('Extensions_Extpgsql.01'); }
}
?>