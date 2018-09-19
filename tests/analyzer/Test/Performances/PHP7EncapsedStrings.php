<?php

namespace Test\Performances;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class PHP7EncapsedStrings extends Analyzer {
    /* 2 methods */

    public function testPerformances_PHP7EncapsedStrings01()  { $this->generic_test('Performances/PHP7EncapsedStrings.01'); }
    public function testPerformances_PHP7EncapsedStrings02()  { $this->generic_test('Performances/PHP7EncapsedStrings.02'); }
}
?>