<?php

final class finalClass {
    final public function finalMethod() {}
          public function nonFinalMethod() {}
}

class nonfinalClass {
    final public function finalMethod2() {}
          public function nonFinalMethod2() {}
}

?>