<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Type_HttpHeader extends Analyzer {
    /* 3 methods */

    public function testType_HttpHeader01()  { $this->generic_test('Type_HttpHeader.01'); }
    public function testType_HttpHeader02()  { $this->generic_test('Type/HttpHeader.02'); }
    public function testType_HttpHeader03()  { $this->generic_test('Type/HttpHeader.03'); }
}
?>