<?php 

namespace A;

use atoum\TEST;
use PHPUnit_Framework_Assert as PHPUnit;

class TestAtoum extends TEST {}
class TestAtoum2 extends TestAtoum {}
class TestAtoum3 extends TestAtoum2 {}

class TestPHPUnit extends PHPUnit {}

class TestSimpleTest extends UnitTestCase {}

