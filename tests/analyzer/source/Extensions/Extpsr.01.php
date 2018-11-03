<?php
// Example from the tests, for Cache (PSR-6)
use Psr\Cache\CacheException;
class MyCacheException extends Exception implements CacheException {}
$ex = new MyCacheException('test');
var_dump($ex instanceof CacheException);
var_dump($ex instanceof Exception);
try {
    throw $ex;
} catch( CacheException $e ) {
    var_dump($e->getMessage());
}
?>