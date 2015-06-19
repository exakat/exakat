<?php
class y {
    private function privateM () {}
    private function privateM2 () {}
    
    private static function privateStaticM3 () {}
    private static function privateStaticM32 () {}

    private static function privateStaticM4 () { print __METHOD__."\n";}
    private static function privateStaticM42 () { print __METHOD__."\n";}
    private static function privateStaticM5 () { print __METHOD__."\n";}
    private static function privateStaticM52 () { print __METHOD__."\n";}
    private static function privateStaticM6 () { print __METHOD__."\n";}
    private static function privateStaticM62 () { print __METHOD__."\n";}
    private static function privateStaticM7 () { print __METHOD__."\n";}
    private static function privateStaticM72 () { print __METHOD__."\n";}

    public function publicM4 () {}
    protected function protectedM5 () {}

    function nonPPPM6 () {
        $this->privateM();
        $this->privateStaticM3();

        $object->privateStaticM32(); // actually, another object

        y::privateStaticM4();
        self::privateStaticM5();
        static::privateStaticM6();
        \y::privateStaticM7();

    }
}

$y = new y;
$y->nonPPPM6();

?>