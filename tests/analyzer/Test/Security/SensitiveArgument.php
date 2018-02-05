<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Security_SensitiveArgument extends Analyzer {
    /* 3 methods */

    public function testSecurity_SensitiveArgument01()  { $this->generic_test('Security_SensitiveArgument.01'); }
    public function testSecurity_SensitiveArgument02()  { $this->generic_test('Security/SensitiveArgument.02'); }
    public function testSecurity_SensitiveArgument03()  { $this->generic_test('Security/SensitiveArgument.03'); }
}
?>