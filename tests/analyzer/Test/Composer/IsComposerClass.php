<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Composer_IsComposerClass extends Analyzer {
    /* 4 methods */

    public function testComposer_IsComposerClass01()  { $this->generic_test('Composer_IsComposerClass.01'); }
    public function testComposer_IsComposerClass02()  { $this->generic_test('Composer_IsComposerClass.02'); }
    public function testComposer_IsComposerClass03()  { $this->generic_test('Composer_IsComposerClass.03'); }
    public function testComposer_IsComposerClass04()  { $this->generic_test('Composer_IsComposerClass.04'); }
}
?>