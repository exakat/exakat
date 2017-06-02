<?php

namespace MyNamespace;

// MyCacheItem implements the PSR-7 CacheItemInterface.
// This MyCacheItem is more of a black hole than a real CacheItem.
class MyCacheItem implements \Psr\Cache\CacheItemInterface {
    public function getKey() {}
    public function get() {}
    public function isHit() {}
    public function set($value) {}
    public function expiresAt($expiration) {}
    public function expiresAfter($time) {}
}

class MyCacheItem2 implements \Psr\Cache\CacheItemInterface2 {}

class MyCacheItem3 implements Psr\Cache\CacheItemInterface {}


?>