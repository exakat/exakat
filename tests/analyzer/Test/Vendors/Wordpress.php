<?php

namespace Test\Vendors;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Wordpress extends Analyzer {
    /* 1 methods */

    public function testVendors_Wordpress01()  { $this->generic_test('Vendors/Wordpress.01'); }
}
?>