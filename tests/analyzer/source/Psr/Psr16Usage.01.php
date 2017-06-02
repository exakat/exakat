<?php
namespace My\SimpleCache;

class MyCache implements \Psr\SimpleCache\CacheInterface {
    public function get($key, $default = null) {}
    public function set($key, $value, $ttl = null) {}
    public function delete($key) {}
    public function clear() {}
    public function getMultiple($keys, $default = null) {}
    public function setMultiple($values, $ttl = null) {}
    public function deleteMultiple($keys) {}
    public function has($key) {}
}

class MyCache2 implements \Psr\SimpleCache\CacheInterface2 { }

class MyCache3 implements Psr\SimpleCache\CacheInterface { }

?>