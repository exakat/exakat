<?php

class AHelper extends Zend_View_Helper_Abstract {}

class AHelper22 extends AHelper21 {}
class AHelper21 extends \Zend_View_Helper_Abstract {}


class AHelper33 extends AHelper32 {}
class AHelper32 extends AHelper31 {}
class AHelper31 extends \Zend_View_Helper_Abstract {}

class NotAHelper {}

class NotAHelperWithExtends extends A {}

?>