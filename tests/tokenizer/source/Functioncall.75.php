<?php 

function a() {
        h(yield 3);
}

function a2() {
        $handler(yield $a->b());
}

function a3() {
        $handler(yield $server->accept());
}

function a4() {
        $handler(yield $server::accept());
}

?>