<?php

// empty trait
trait t { }

// Another empty trait
trait t2 {
    use t; 
}

// Another empty trait
trait t22 {
    use t; 
    use t22;
}

// A not empty trait
trait t3 {
    use t; 
    function foo() {}
}

trait t4 {
    use t; 

    private $bar;
}


?>