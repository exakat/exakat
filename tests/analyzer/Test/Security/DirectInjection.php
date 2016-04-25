<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Security_DirectInjection extends Analyzer {
    /* 5 methods */

    public function testSecurity_DirectInjection01()  { $this->generic_test('Security_DirectInjection.01'); }
    public function testSecurity_DirectInjection02()  { $this->generic_test('Security/DirectInjection.02'); }
    public function testSecurity_DirectInjection03()  { $this->generic_test('Security/DirectInjection.03'); }
    public function testSecurity_DirectInjection04()  { $this->generic_test('Security/DirectInjection.04'); }
    public function testSecurity_DirectInjection05()  { $this->generic_test('Security/DirectInjection.05'); }
}
?>