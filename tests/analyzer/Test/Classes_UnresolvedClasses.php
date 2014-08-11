<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_UnresolvedClasses extends Analyzer {
    /* 9 methods */

    public function testClasses_UnresolvedClasses01()  { $this->generic_test('Classes_UnresolvedClasses.01'); }
    public function testClasses_UnresolvedClasses02()  { $this->generic_test('Classes_UnresolvedClasses.02'); }
    public function testClasses_UnresolvedClasses03()  { $this->generic_test('Classes_UnresolvedClasses.03'); }
    public function testClasses_UnresolvedClasses04()  { $this->generic_test('Classes_UnresolvedClasses.04'); }
    public function testClasses_UnresolvedClasses05()  { $this->generic_test('Classes_UnresolvedClasses.05'); }
    public function testClasses_UnresolvedClasses06()  { $this->generic_test('Classes_UnresolvedClasses.06'); }
    public function testClasses_UnresolvedClasses07()  { $this->generic_test('Classes_UnresolvedClasses.07'); }
    public function testClasses_UnresolvedClasses08()  { $this->generic_test('Classes_UnresolvedClasses.08'); }
    public function testClasses_UnresolvedClasses09()  { $this->generic_test('Classes_UnresolvedClasses.09'); }
}
?>