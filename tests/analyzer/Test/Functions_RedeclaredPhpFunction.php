<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Functions_RedeclaredPhpFunction extends Analyzer {
    /* 1 methods */

    public function testFunctions_RedeclaredPhpFunction01()  { $this->generic_test('Functions_RedeclaredPhpFunction.01'); }
}
?>