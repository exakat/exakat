<?php
function a():array { $a++; }

function b():callable { $a++; }

function c():float { $a++; }
function c2():string { $a++; }
function c3():int { $a++; }
function c4():boolean { $a++; }

function d():Stdclass { $a++; }


