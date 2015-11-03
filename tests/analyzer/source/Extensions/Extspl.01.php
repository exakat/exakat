<?php

foreach (new DirectoryIterator('../moodle') as $fileInfo) {
    if($fileInfo->isDot()) continue;
    echo $fileInfo->getFilename() . "<br>\n";
}

?>