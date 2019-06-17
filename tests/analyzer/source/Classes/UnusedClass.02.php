<?php 

namespace A;

use PHPUnit_Framework_Assert as PHPUnit;

class TestAtoum extends PHPUnit {}
class TestAtoum2 extends TestAtoum {}
class TestAtoum3 extends TestAtoum2 {}

// Test is not recognized as a test class. 
class TestB extends TEST {}
class TestB2 extends TestB {}
class TestB3 extends TestB2 {}

class normalClass {}
