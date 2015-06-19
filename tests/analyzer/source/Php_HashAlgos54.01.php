<?php

echo hash('salsa10', 'The quick brown fox jumped over the lazy dog.');
echo hash('fnv1a64', 'The quick brown fox jumped over the lazy dog.');

echo hash('ripemd160', 'The quick brown fox jumped over the lazy dog.');
?>