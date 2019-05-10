<?php

if (file_exists('exakat.phar')) {
    unlink('exakat.phar');
}

shell_exec('composer update --no-dev');

$php = file_get_contents('library/Exakat/Exakat.php');
$build = preg_match('/    const BUILD = (\d+);/', $php, $r);
$neo_build = ++$r[1];

$php = preg_replace('/    const BUILD = (\d+);/', '    const BUILD = '.$neo_build.';', $php);
file_put_contents('library/Exakat/Exakat.php', $php);

$begin = hrtime(true);
// create with alias "project.phar"
$phar = new Phar('exakat.phar', 0, 'exakat.phar');
// add all files in the project

$phar->startBuffering();

$directories = array('/library', 
                     '/data',
                     '/human',
                     '/media',
                     '/server',
                     '/vendor',
                     );
                     
foreach($directories as $directory) {
    $phar->buildFromIterator(
        new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(__DIR__.$directory, FilesystemIterator::SKIP_DOTS)
        ),
        __DIR__);
}

$phar->addFile(__DIR__ . '/exakat', 'exakat');
$defaultStub = $phar->createDefaultStub('exakat', 'exakat');
$stub = "#!/usr/bin/env php \n$defaultStub";

$phar->setStub($stub);

$phar->stopBuffering();

$end = hrtime(true);

print number_format(filesize('exakat.phar') / 1024 / 1024, 2).' Mb'.PHP_EOL;
print number_format(($end - $begin) / 1000000).' ms'.PHP_EOL;
print shell_exec('php exakat.phar').PHP_EOL;

//shell_exec('composer update');

copy('exakat.phar', '../release/exakat.phar');

?>