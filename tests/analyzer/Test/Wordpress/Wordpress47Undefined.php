<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Wordpress_Wordpress47Undefined extends Analyzer {
    /* 1 methods */

    public function testWordpress_Wordpress47Undefined01()  { $this->generic_test('Wordpress/Wordpress47Undefined.01'); }
}
?>