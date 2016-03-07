<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Namespaces_GlobalImport extends Analyzer {
    /* 3 methods */

    public function testNamespaces_GlobalImport01()  { $this->generic_test('Namespaces/GlobalImport.01'); }
    public function testNamespaces_GlobalImport02()  { $this->generic_test('Namespaces/GlobalImport.02'); }
    public function testNamespaces_GlobalImport03()  { $this->generic_test('Namespaces/GlobalImport.03'); }
}
?>