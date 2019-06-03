<?php

$expected     = array('\'http://www.phpOK.net/\'',
                      '\'http://dams:pass@www.php.net/\'',
                      '\'http://dams@www.php.net/\'',
                      '\'https://dams@www.php.net:83/\'',
                      '\'http://www.ibm.com/developerworks/news/dw_dwtp.rss\'',
                      '\'http://myportal.com:10040/wps/proxy/http/myotherportal.com%3a1234/sitemap\'',
                      '\'ftps://www-01.ibm.com/software/swnews/swnews.nsf/swnewsrss?openview&RestrictToCategory=lotus\'',
                      '\'strangeproto://www.php.net/\'',
                      '\'http://xn--diseolatinoamericano-66b.com/\'',
                      '\'http://www.php.net\'',
                      '\'http://www.法国.cn/\'',
                      '"http://www.google.com$x"',
                     );

$expected_not = array('\'http:/www.php.net/\'',
                      '\'https:/www.php.net/\'',
                      'http://www.google.com',
                     );

?>