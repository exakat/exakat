<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Security_DontEchoError extends Analyzer {
    /* 4 methods */

    public function testSecurity_DontEchoError01()  { $this->generic_test('Security/DontEchoError.01'); }
    public function testSecurity_DontEchoError02()  { $this->generic_test('Security/DontEchoError.02'); }
    public function testSecurity_DontEchoError03()  { $this->generic_test('Security/DontEchoError.03'); }
    public function testSecurity_DontEchoError04()  { $this->generic_test('Security/DontEchoError.04'); }
}
?>