<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Functions_EmptyFunction extends Analyzer {
    /* 1 methods */

    public function testFunctions_EmptyFunction01()  { $this->generic_test('Functions_EmptyFunction.01'); }
}
?>