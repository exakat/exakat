<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Functions_EmptyFunction extends Analyzer {
    /* 3 methods */

    public function testFunctions_EmptyFunction01()  { $this->generic_test('Functions_EmptyFunction.01'); }
    public function testFunctions_EmptyFunction02()  { $this->generic_test('Functions_EmptyFunction.02'); }
    public function testFunctions_EmptyFunction03()  { $this->generic_test('Functions_EmptyFunction.03'); }
}
?>