<?php

$array = array(
    match ($test) { 1 => 'a', 2 => 'b' },
);

$array = [
    match ($test) { 1 => 'a', 2 => 'b' } => 'dynamic keys, woho!',
];

$array = [
    // In order: match arrow, array arrow, match arrow, array arrow, match arrow, array arrow, match arrow.
    match ($test) { 1 => [ 1 => 'a'], 2 => 'b' } => match ($test) { 1 => [ 1 => 'a'], 2 => 'b' },
];

