<?php

class a {
    private static $allStatic = 1;
    private $noneStatic;
    private static $mixtedStatic;
    private static $varAndStatic;
}

class b {
    private static $allStatic = 1;
    private $noneStatic;
    private $mixtedStatic;
    var  X $varAndStatic;
}


?>