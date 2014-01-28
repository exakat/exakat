<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Type_HttpHeader extends Analyzer {
    /* 1 methods */

    public function testType_HttpHeader01()  { $this->generic_test('Type_HttpHeader.01'); }
}
?>