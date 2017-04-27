<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Namespaces_ShouldMakeAlias extends Analyzer {
    /* 7 methods */

    public function testNamespaces_ShouldMakeAlias01()  { $this->generic_test('Namespaces/ShouldMakeAlias.01'); }
    public function testNamespaces_ShouldMakeAlias02()  { $this->generic_test('Namespaces/ShouldMakeAlias.02'); }
    public function testNamespaces_ShouldMakeAlias03()  { $this->generic_test('Namespaces/ShouldMakeAlias.03'); }
    public function testNamespaces_ShouldMakeAlias04()  { $this->generic_test('Namespaces/ShouldMakeAlias.04'); }
    public function testNamespaces_ShouldMakeAlias05()  { $this->generic_test('Namespaces/ShouldMakeAlias.05'); }
    public function testNamespaces_ShouldMakeAlias06()  { $this->generic_test('Namespaces/ShouldMakeAlias.06'); }
    public function testNamespaces_ShouldMakeAlias07()  { $this->generic_test('Namespaces/ShouldMakeAlias.07'); }
}
?>