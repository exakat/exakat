<?php

$classes = glob('Test/*/*.php');


foreach($classes as $class) {
//    print $class."\n";
    
    $php = file_get_contents($class);
    if (strpos($php, "include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');") !== false) {
        $php = str_replace("include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');",
                           "include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');",
                           $php);
        file_put_contents($class, $php);
//        print "Not to update : " .$class."\n";
    }
//    
    continue;
    //$class = 'Test/Classes_NonStaticMethodsCalledStatic.php';

    $file = basename($class);
    list($folder, $analyzer) = explode('_', $file);

    if (!file_exists('Test/'.$folder)) {
        print "Creation de Test/$folder\n";
        mkdir('Test/'.$folder, 0755);
        mkdir('source/'.$folder, 0755);
        mkdir('exp/'.$folder, 0755);
    }
    print "mv $class Test/$folder/$analyzer\n";
    rename($class, "Test/$folder/$analyzer");

    // source
    $sources = glob('source/'.str_replace('.php', '', $file).'*');
    foreach($sources as $source) {
        print "mv $source ".str_replace('_', '/', $source)."\n";
        rename($source, str_replace('_', '/', $source));
    }

    // exp
    $exps = glob('exp/'.str_replace('.php', '', $file).'*');
    foreach($exps as $exp) {
        print "mv $exp ".str_replace('_', '/', $exp)."\n";
        rename($exp, str_replace('_', '/', $exp));
    }
}
?>