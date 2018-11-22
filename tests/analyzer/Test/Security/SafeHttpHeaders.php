<?php

namespace Test\Security;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class SafeHttpHeaders extends Analyzer {
    /* 2 methods */

    public function testSecurity_SafeHttpHeaders01()  { $this->generic_test('Security/SafeHttpHeaders.01'); }
    public function testSecurity_SafeHttpHeaders02()  { $this->generic_test('Security/SafeHttpHeaders.02'); }
}
?>