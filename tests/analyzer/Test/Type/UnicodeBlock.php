<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class UnicodeBlock extends Analyzer {
    /* 2 methods */

    public function testType_UnicodeBlock01()  { $this->generic_test('Type_UnicodeBlock.01'); }
    public function testType_UnicodeBlock02()  { $this->generic_test('Type_UnicodeBlock.02'); }
}
?>