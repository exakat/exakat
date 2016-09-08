<?php

namespace A ;

// Recursive. OK (Not found)
function a() {    a(); }

// loop 2
function a1() {    a2(); }
function a2() {    a1();
                   $o->{$p}(); }

// loop 3
function b1() {    b2(); }
function b2() {    b3();
                   $o->{$p}(); }
function b3() {    b1(); }

// loop 4
function c1() {    c2(); }
function c2() {    c3(); }
function c3() {    c4();
                   $o->{$p}(); }
function c4() {    c1(); }

// Not loop
function d1() {    d2(); }
function d2() {    d3(); }
function d3() {    d4(); }
function d4() {    a(); }

?>