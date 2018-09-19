<?php

namespace Test\Composer;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class IsComposerInterface extends Analyzer {
    /* 3 methods */

    public function testComposer_IsComposerInterface01()  { $this->generic_test('Composer_IsComposerInterface.01'); }
    public function testComposer_IsComposerInterface02()  { $this->generic_test('Composer_IsComposerInterface.02'); }
    public function testComposer_IsComposerInterface03()  { $this->generic_test('Composer/IsComposerInterface.03'); }
}
?>