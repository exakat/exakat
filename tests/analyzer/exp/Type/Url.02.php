<?php

$expected     = array('\'http://www.\' . DOMAIN . \'.net/\'',
                      '<<<URL
http://svn.goofy.com/yes/and/no.html
URL',
                     );

$expected_not = array('\'http://www.\'',
                      '\'http://www.\'',
                      '\'http://www.\' . DOMAIN2 . \'.net/\'',
                     );

?>