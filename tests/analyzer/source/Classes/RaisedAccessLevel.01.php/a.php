<?php

class A {
    private $privateOnly;
    private $privateThenPrivate;
    private $privateThenProtected;
    private $privateThenPublic;

    protected $protectedOnly;
    protected $protectedThenPrivate;
    protected $protectedThenProtected;
    protected $protectedThenPublic;

    public $publicOnly;
    public $publicThenPrivate;
    public $publicThenProtected;
    public $publicThenPublic;
}

?>