<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Namespaces_CouldUseAlias extends Analyzer {
    /* 5 methods */

    public function testNamespaces_CouldUseAlias01()  { $this->generic_test('Namespaces/CouldUseAlias.01'); }
    public function testNamespaces_CouldUseAlias02()  { $this->generic_test('Namespaces/CouldUseAlias.02'); }
    public function testNamespaces_CouldUseAlias03()  { $this->generic_test('Namespaces/CouldUseAlias.03'); }
    public function testNamespaces_CouldUseAlias04()  { $this->generic_test('Namespaces/CouldUseAlias.04'); }
    public function testNamespaces_CouldUseAlias05()  { $this->generic_test('Namespaces/CouldUseAlias.05'); }
}
?>