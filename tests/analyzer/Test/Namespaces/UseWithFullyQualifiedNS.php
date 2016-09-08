<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Namespaces_UseWithFullyQualifiedNS extends Analyzer {
    /* 3 methods */

    public function testNamespaces_UseWithFullyQualifiedNS01()  { $this->generic_test('Namespaces_UseWithFullyQualifiedNS.01'); }
    public function testNamespaces_UseWithFullyQualifiedNS02()  { $this->generic_test('Namespaces/UseWithFullyQualifiedNS.02'); }
    public function testNamespaces_UseWithFullyQualifiedNS03()  { $this->generic_test('Namespaces/UseWithFullyQualifiedNS.03'); }
}
?>