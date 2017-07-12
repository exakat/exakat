<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Security_IndirectInjection extends Analyzer {
    /* 2 methods */

    public function testSecurity_IndirectInjection01()  { $this->generic_test('Security/IndirectInjection.01'); }
    public function testSecurity_IndirectInjection02()  { $this->generic_test('Security/IndirectInjection.02'); }
}
?>