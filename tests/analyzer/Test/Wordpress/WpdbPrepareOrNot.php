<?php

namespace Test\Wordpress;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class WpdbPrepareOrNot extends Analyzer {
    /* 1 methods */

    public function testWordpress_WpdbPrepareOrNot01()  { $this->generic_test('Wordpress/WpdbPrepareOrNot.01'); }
}
?>