<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Security_NoNetForXmlLoad extends Analyzer {
    /* 4 methods */

    public function testSecurity_NoNetForXmlLoad01()  { $this->generic_test('Security/NoNetForXmlLoad.01'); }
    public function testSecurity_NoNetForXmlLoad02()  { $this->generic_test('Security/NoNetForXmlLoad.02'); }
    public function testSecurity_NoNetForXmlLoad03()  { $this->generic_test('Security/NoNetForXmlLoad.03'); }
    public function testSecurity_NoNetForXmlLoad04()  { $this->generic_test('Security/NoNetForXmlLoad.04'); }
}
?>