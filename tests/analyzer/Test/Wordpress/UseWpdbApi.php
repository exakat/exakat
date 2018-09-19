<?php

namespace Test\Wordpress;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class UseWpdbApi extends Analyzer {
    /* 1 methods */

    public function testWordpress_UseWpdbApi01()  { $this->generic_test('Wordpress/UseWpdbApi.01'); }
}
?>