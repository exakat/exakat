<?php
class a1 extends a1 { private function m1() { print __CLASS__."\n"; }
           private function m6() { print __CLASS__."\n"; } }
class a2 extends a1 { private function m2() { print __CLASS__."\n"; } 
                      private function m7() { print __CLASS__."\n"; } 
                      }
class a3 extends a2 { private function m3() { print __CLASS__."\n"; }
                      private function m5() { print __CLASS__."\n"; } 
                    }
class a4 extends a3 { 
            private function m4() { print __CLASS__."\n"; 

            static::m5(); // fails
            parent::m6(); // fails
            self::m7();   // fails

            static::m15(); // non-existant method, so no find
            parent::m16(); // non-existant method, so no find
            self::m17();   // non-existant method, so no find

            static::m25(); // non-existant method, so no find
            self::m27();   // non-existant method, so no find

        return new static($msg->shift(), $msg->shift(), $msg->toArray());
                                            }
            private function m25() { print __CLASS__."\n"; }
            private function m27() { print __CLASS__."\n"; }
            }

$o = new a4;

a4::m1();  // fails
a4::m2();  // fails
a4::m3();  // fails
a4::m4();  // OK (WOn't show)

a4::m8();  // non existant  
a1::m9();  // non existant 

?>