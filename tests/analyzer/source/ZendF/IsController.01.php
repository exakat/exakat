<?php

class AController extends Zend_Controller_Action {}

class AController22 extends AController21 {}
class AController21 extends \Zend_Controller_Action {}


class AController33 extends AController32 {}
class AController32 extends AController31 {}
class AController31 extends \Zend_Controller_Action {}

class NotAController {}

class NotAControllerWithExtends extends A {}

?>