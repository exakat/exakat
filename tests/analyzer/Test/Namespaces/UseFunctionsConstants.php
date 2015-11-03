<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Namespaces_UseFunctionsConstants extends Analyzer {
    /* 1 methods */

    public function testNamespaces_UseFunctionsConstants01()  { $this->generic_test('Namespaces_UseFunctionsConstants.01'); }
}
?>