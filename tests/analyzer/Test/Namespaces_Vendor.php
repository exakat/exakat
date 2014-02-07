<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Namespaces_Vendor extends Analyzer {
    /* 1 methods */

    public function testNamespaces_Vendor01()  { $this->generic_test('Namespaces_Vendor.01'); }
}
?>