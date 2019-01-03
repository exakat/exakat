<?php

trait emptyTrait {}

trait nonEmptyTrait{
    public function y() {}
}

trait nonEmptyTrait2 {
    private $foo = 2;
}

trait emptyTrait2 { use emptyTrait;}


trait emptySelfTrait { use emptySelfTrait;}

?>