<?php

// class in a property is not defined
class x {
    protected $a = self::B;
    protected $b = parent::B;
    
    const B = 1;
    const Bye = 2;
}

class y extends x {
    protected $a = self::By;
    protected $b = parent::By;
    protected $c = parent::Bye;
    
    const By = 1;
}

?>