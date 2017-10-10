<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Security_AnchorRegex extends Analyzer {
    /* 2 methods */

    public function testSecurity_AnchorRegex01()  { $this->generic_test('Security/AnchorRegex.01'); }
    public function testSecurity_AnchorRegex02()  { $this->generic_test('Security/AnchorRegex.02'); }
}
?>