#!/bin/sh 
php56 -d short_open_tag=1 -r "echo count(token_get_all(file_get_contents('$1'))).\" $1\n\";" 2>>/dev/null || true
 