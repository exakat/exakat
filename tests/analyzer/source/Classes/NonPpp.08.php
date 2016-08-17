<?php

interface i { function x();
              static function x2();
          }

class cPublic implements i { 
    public function x() {}
    public static function x2() {}
}

class cPrivate implements i { 
    private function x() {}
    private static function x2() {}
}

$c = new c();
$c->x();
?>