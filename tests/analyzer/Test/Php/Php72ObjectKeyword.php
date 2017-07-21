<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Php_Php72ObjectKeyword extends Analyzer {
    /* 1 methods */

    public function testPhp_Php72ObjectKeyword01()  { $this->generic_test('Php/Php72ObjectKeyword.01'); }
}
?>