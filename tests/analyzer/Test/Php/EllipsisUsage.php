<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class EllipsisUsage extends Analyzer {
    /* 4 methods */

    public function testPhp_EllipsisUsage01()  { $this->generic_test('Php/EllipsisUsage.01'); }
    public function testPhp_EllipsisUsage02()  { $this->generic_test('Php/EllipsisUsage.02'); }
    public function testPhp_EllipsisUsage03()  { $this->generic_test('Php/EllipsisUsage.03'); }
    public function testPhp_EllipsisUsage04()  { $this->generic_test('Php/EllipsisUsage.04'); }
}
?>