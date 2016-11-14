cd ../..

# Normal test
php exakat init -p clean -v

mkdir projects/clean/code
echo '<?php phpinfo(); ?>' > projects/clean/code/index.php

php exakat project -p clean

php exakat clean -p nonExistant
php exakat clean -p clean

php exakat remove -p nonExistant
php exakat remove -p clean

cd -

