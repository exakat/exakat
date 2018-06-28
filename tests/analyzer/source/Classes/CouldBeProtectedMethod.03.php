<?php

use a as b;

class a {
    public    function apublicButSBProtected(){}
    public    function apublicButSBProtected2(){}
    public    function apublicButSBProtected3(){}
    public    function apublicButReally(){}
    public    function apublicButReally2(){}
    public    function apublicButReally3(){}

    public    function unused(){}

    function b() {
        $this->apublicButSBProtected()
             ->apublicButSBProtected2()
             ->apublicButSBProtected2();

        $a->apublicButReally()
          ->apublicButReally2()
          ->apublicButReally3();
    }
}
?>