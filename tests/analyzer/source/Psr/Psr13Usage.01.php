<?php

namespace XXX;

class MyLink implements \Psr\Link\LinkInterface {

    public function getHref() {}
    public function isTemplated() {}
    public function getRels() {}
    public function getAttributes() {}
}

class MyLink implements Psr\Link\LinkInterface { }

class MyLink implements \Psr\Link\LinkInterface2 { }

?>