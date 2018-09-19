<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class HttpHeader extends Analyzer {
    /* 3 methods */

    public function testType_HttpHeader01()  { $this->generic_test('Type_HttpHeader.01'); }
    public function testType_HttpHeader02()  { $this->generic_test('Type/HttpHeader.02'); }
    public function testType_HttpHeader03()  { $this->generic_test('Type/HttpHeader.03'); }
}
?>