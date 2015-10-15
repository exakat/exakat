<?php

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class A {
    public function b(array $queue, Psr\Http\Message\RequestInterface $fullPath, RequestInterface $justString, Request $alias)
    {
        $a++;
    }
}
