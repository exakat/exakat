<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Wordpress_UseWpFunctions extends Analyzer {
    /* 1 methods */

    public function testWordpress_UseWpFunctions01()  { $this->generic_test('Wordpress/UseWpFunctions.01'); }
}
?>