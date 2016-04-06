cd ../..
php exakat server -v
php -r "echo file_get_contents('http://127.0.0.1:7447');"
php -r "echo file_get_contents('http://127.0.0.1:7447/status/');"
php exakat server -stop -v
cd -

