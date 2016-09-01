<?php

namespace A ;

// Recursive. OK (Not found)
function a() {    a(); }

// loop 2
function a2() {    \B\a1(); }

// loop 3
function b1() {    \B\b2(); }

// loop 4
function c1() {    \A\c2(); }
function c2() {    \B\c3(); }

namespace B ;

// loop 2
function a1() {    \A\a2(); }

// loop 3
function b2() {    \B\b3(); }
function b3() {    \A\b1(); }

// loop 4
function c3() {    \B\c4(); }
function c4() {    \A\c1(); }

// Not loop
function d1() {    d2(); }
function d2() {    d3(); }
function d3() {    d4(); }
function d4() {    a(); }

function e() {    $o->e(); }
function ef() {    $e(); }

?>