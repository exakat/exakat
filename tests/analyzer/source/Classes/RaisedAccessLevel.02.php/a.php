<?php

class A {
    private $privateOnly = 1;
    private $privateThenPrivate = 12;
    private $privateThenProtected = 13;
    private $privateThenPublic = 14;

    protected $protectedOnly = 15;
    protected $protectedThenPrivate = 16;
    protected $protectedThenProtected = 17;
    protected $protectedThenPublic = 18;

    public $publicOnly = 19;
    public $publicThenPrivate = 20;
    public $publicThenProtected = 21;
    public $publicThenPublic = 22;
}

?>