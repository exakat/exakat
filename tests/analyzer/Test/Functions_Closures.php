<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Functions_Closures extends Analyzer {
    /* 2 methods */

    public function testFunctions_Closures01()  { $this->generic_test('Functions_Closures.01'); }
    public function testFunctions_Closures02()  { $this->generic_test('Functions_Closures.02'); }
}
?>