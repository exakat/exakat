<?php

clone fooString();
clone fooX();
clone fooXorNull();
clone fooVoid();

function fooString() : string {}
function fooX() : X {}
function fooXorNull() : ?X {}
function fooVoid() {}

?>