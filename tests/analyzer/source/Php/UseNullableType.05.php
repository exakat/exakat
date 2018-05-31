<?php

function (?string $x1) {};

function (?string $x2, ?int $y) {};

function (string $x3, int $y) : ?array {};

function (string $x4, ?callable $y) : ?array {};

function (?string $x5, ?callable $y) : ?array {};

function bar(string $x6) {};

function (string $x7, int $y) {};

function (string $x8, int $y) : array {};

function (string $x9, callable $y) : array {};

function (string $x10, callable $y) : array {};

?>