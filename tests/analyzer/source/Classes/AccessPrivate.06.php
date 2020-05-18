<?php

// Classes/AccessPrivate
class x {
    protected function wrapper()
    {
        return get_class($this->resource)::$wrap;
    }

    protected function c()
    {
        return C::$P + C::$p;
    }
}

class c {
    private $P;
    public $p;
}

?>