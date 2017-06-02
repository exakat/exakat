<?php

namespace XX;

class MyContainerInterface implements \Psr\Container\ContainerInterface {
    public function get($id) {}
    public function has($id) {}
}

class MyContainerInterface2 implements \Psr\Container\ContainerInterface2 {}

class MyContainerInterface3 implements Psr\Container\ContainerInterface {}

?>