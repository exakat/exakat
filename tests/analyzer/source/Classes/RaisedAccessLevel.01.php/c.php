<?php

class C {
//    private $privateOnly;
    private $privateThenPrivate;
    protected $privateThenProtected;
    public $privateThenPublic;

//    protected $protectedOnly;
    private $protectedThenPrivate;
    protected $protectedThenProtected;
    public $protectedThenPublic;

//    public $publicOnly;
    private $publicThenPrivate;
    protected $publicThenProtected;
    public $publicThenPublic;
}

?>