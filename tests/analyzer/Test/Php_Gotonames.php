<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Php_Gotonames extends Analyzer {
    /* 1 methods */

    public function testPhp_Gotonames01()  { $this->generic_test('Php_Gotonames.01'); }
}
?>