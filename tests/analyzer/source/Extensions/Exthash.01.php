<?php
/* Create a file to calculate hash of */
file_put_contents('example.txt', 'The quick brown fox jumped over the lazy dog.');

echo hash_file('md5', 'example.txt');
?>