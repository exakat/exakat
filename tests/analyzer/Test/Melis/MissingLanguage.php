<?php

namespace Test\Melis;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class MissingLanguage extends Analyzer {
    /* 2 methods */

    public function testMelis_MissingLanguage01()  { $this->generic_test('Melis/MissingLanguage.01'); }
    public function testMelis_MissingLanguage02()  { $this->generic_test('Melis/MissingLanguage.02'); }
}
?>