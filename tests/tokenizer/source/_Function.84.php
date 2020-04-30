<?php

fn &($a1) => 1;
fn &(&$a2) => $a;

fn &($a1) : int => 1;
fn &(&$a2) : \a\b\c => function ($a) { return $b + 3;};

