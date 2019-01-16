<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class TypedProperties extends Tokenizer {
    /* 9 methods */

    public function testTypedProperties01()  { $this->generic_test('TypedProperties.01'); }
    public function testTypedProperties02()  { $this->generic_test('TypedProperties.02'); }
    public function testTypedProperties03()  { $this->generic_test('TypedProperties.03'); }
    public function testTypedProperties04()  { $this->generic_test('TypedProperties.04'); }
    public function testTypedProperties05()  { $this->generic_test('TypedProperties.05'); }
    public function testTypedProperties06()  { $this->generic_test('TypedProperties.06'); }
    public function testTypedProperties07()  { $this->generic_test('TypedProperties.07'); }
    public function testTypedProperties08()  { $this->generic_test('TypedProperties.08'); }
    public function testTypedProperties09()  { $this->generic_test('TypedProperties.09'); }
}
?>