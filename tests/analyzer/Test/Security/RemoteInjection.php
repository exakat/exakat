<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Security_RemoteInjection extends Analyzer {
    /* 1 methods */

    public function testSecurity_RemoteInjection01()  { $this->generic_test('Security_RemoteInjection.01'); }
}
?>