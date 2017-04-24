<?php

function ab() {    ba(); }
function ba() {    ab(); }

function abc() {    bca(); }
function bca() {    cab(); }
function cab() {    abc(); }

function abcSub() {    bcaSub(); }
function bcaSub() {    cabSub(); }
function cabSub() {    function C() { abcSub(); } }


?>