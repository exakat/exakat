<?php

namespace Test\Wordpress;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class WpdbPrepareForVariables extends Analyzer {
    /* 2 methods */

    public function testWordpress_WpdbPrepareForVariables01()  { $this->generic_test('Wordpress/WpdbPrepareForVariables.01'); }
    public function testWordpress_WpdbPrepareForVariables02()  { $this->generic_test('Wordpress/WpdbPrepareForVariables.02'); }
}
?>