<?php

namespace Test\Security;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class AnchorRegex extends Analyzer {
    /* 2 methods */

    public function testSecurity_AnchorRegex01()  { $this->generic_test('Security/AnchorRegex.01'); }
    public function testSecurity_AnchorRegex02()  { $this->generic_test('Security/AnchorRegex.02'); }
}
?>