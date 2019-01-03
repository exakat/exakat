<?php

trait emptyTrait {use emptySelfTrait;use emptySelfTrait;use emptySelfTrait;}

trait nonEmptyTrait{
    public function y() {}
    public function y2() {}
    public function y3() {}
    public function y4() {}
}

trait nonEmptyTrait2 {
    private $foo = 2;
    public function y() {}
}

?>