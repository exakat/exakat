<?php

    basename(iconv_substr($path), 0, 1);
    basename(substr($path), 0, 1);
    basename(mb_substr($path), 0, 1);

    basename(str_replace(0, 1, $path));
    basename(str_ireplace(0, 1, $path));

    basename(substr(1, $path), 1);
    basename(substr(1, $basename));

    $z->basename(mb_substr($path, 0, 1));

?>