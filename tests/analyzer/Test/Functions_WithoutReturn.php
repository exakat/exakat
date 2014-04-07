<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Functions_WithoutReturn extends Analyzer {
    /* 1 methods */

    public function testFunctions_WithoutReturn01()  { $this->generic_test('Functions_WithoutReturn.01'); }
}
?>