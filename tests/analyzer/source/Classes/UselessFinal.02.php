<?php

final class finalClass3 {
    final public function finalMethod() {}
    final public function finalMethod2() {}
    final public function finalMethod3() {}
          public function nonFinalMethod() {}
}

class nonfinalClass {
    final public function finalMethod2() {}
          public function nonFinalMethod2() {}
}

final class finalClass4 {
          protected function nonFinalMethod2() {}
          private function nonFinalMethod3() {}
          static function nonFinalMethod4() {}
    public final function finalMethod4() {}
          public function nonFinalMethod() {}
}

?>