<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Functions_ShouldUseConstants extends Analyzer {
    /* 3 methods */

    public function testFunctions_ShouldUseConstants01()  { $this->generic_test('Functions_ShouldUseConstants.01'); }
    public function testFunctions_ShouldUseConstants02()  { $this->generic_test('Functions_ShouldUseConstants.02'); }
    public function testFunctions_ShouldUseConstants03()  { $this->generic_test('Functions_ShouldUseConstants.03'); }
}
?>