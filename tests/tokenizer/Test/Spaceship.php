<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Spaceship extends Tokenizer {
    /* 7 methods */

    public function testSpaceship01()  { $this->generic_test('Spaceship.01'); }
    public function testSpaceship02()  { $this->generic_test('Spaceship.02'); }
    public function testSpaceship03()  { $this->generic_test('Spaceship.03'); }
    public function testSpaceship04()  { $this->generic_test('Spaceship.04'); }
    public function testSpaceship05()  { $this->generic_test('Spaceship.05'); }
    public function testSpaceship06()  { $this->generic_test('Spaceship.06'); }
    public function testSpaceship07()  { $this->generic_test('Spaceship.07'); }
}
?>