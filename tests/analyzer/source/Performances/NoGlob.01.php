<?php

// Scandir without sorting is the fastest. 
scandir('docs/', SCANDIR_SORT_NONE);
scandir('docs/', SCANDIR_SORT_ASCENDING);
scandir('docs/', SCANDIR_SORT_DESCENDING);

// Scandir sorts files by default. Same as above, but with sorting
scandir('docs/');

// glob sorts files by default. Same as below, but no sorting
glob('docs/*', GLOB_NOSORT);

// glob sorts files by default. This is the slowest version
\glob('docs/*');

foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir)
        {
            $files = array_merge($files, glob_recursive($dir.'/'.basename($pattern), $flags));
        }

foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOESCAPE) as $dir)
        {
            $files = array_merge($files, glob_recursive($dir.'/'.basename($pattern), $flags));
        }
?>