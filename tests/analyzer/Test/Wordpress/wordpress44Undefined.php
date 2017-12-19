<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class wordpress_wordpress44Undefined extends Analyzer {
    /* 1 methods */

    public function testwordpress_wordpress44Undefined01()  { $this->generic_test('wordpress/wordpress44Undefined.01'); }
}
?>