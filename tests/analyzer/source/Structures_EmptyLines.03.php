<?php   

function y ($y) { $y++;}; // One empty line

function ($y) { $y++;}; // Not empty line (CLosure)

function y2 ($y) { $y++;};// One empty line

function ($y) use ($b) { $y++;};// Not empty line (CLosure)

function y3 ($y) { $y++;};// One empty line

?>