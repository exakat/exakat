<?php

class a {
    public $apu, $apud = 2, $apu2, $apud2 = 3;
    protected $apro, $aprod = 2, $apro2, $aprod2 = 3;
    private $apri, $aprid = 2, $apri2, $aprid2 = 3;
    static $asta, $astad = 2, $asta2, $astad2 = 3;
    public static $apusta, $apustad = 2, $apusta2, $apustad2 = 3;
    
    function ab() {
        $this->apu2 = 2;
        $this->apud2 = 2;
        $this->apro2 = 2;
        $this->aprod2 = 2;
        $this->apri2 = 2;
        $this->aprid2 = 2;
        self::$asta2 = 2;
        self::$astad2 = 2;
        self::$apusta2 = 2;
        self::$apustad2 = 2;
    }
}
?>