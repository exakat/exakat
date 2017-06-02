<?php

namespace MyNamespace;

class MyServerRequest implements \Psr\Http\Message\ServerRequestInterface  {
    public function getServerParams() {}
    public function getCookieParams() {}
    public function withCookieParams(array $cookies) {}
    public function getQueryParams() {}
    public function withQueryParams(array $query) {}
    public function getUploadedFiles() {}
    public function withUploadedFiles(array $uploadedFiles) {}
    public function getParsedBody() {}
    public function withParsedBody($data) {}
    public function getAttributes() {}
    public function getAttribute($name, $default = null) {}
    public function withAttribute($name, $value) {}
    public function withoutAttribute($name) {}
}

class MyServerRequest2 implements  \Psr\Http\Message\ServerRequestInterface2  { }

class MyServerRequest3 implements  Psr\Http\Message\ServerRequestInterface  { }


?>