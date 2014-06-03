#!/bin/sh 
php56 -r "echo count(token_get_all(file_get_contents('$1'))).\"\n\";" 2>>/dev/null || true
 