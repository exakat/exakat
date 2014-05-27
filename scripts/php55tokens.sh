#!/bin/sh 
php55 -r "echo count(token_get_all(file_get_contents('$1'))).\"\n\";" || true
 