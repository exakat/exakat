<?php


use A\T as ttt;
use T as TT;

class A extends E implements I1,I2 {
    use T;
    use TT;
    use ttt;
    use undefined;
}

trait T {}

interface I1{}
interface I2{}
class E {}

?>
