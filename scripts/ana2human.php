<?php

include(dirname(__DIR__).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_library');

$analyzers = glob('library/Analyzer/*/*');

$documented = 0;
foreach($analyzers as $analyzer) {
    $analyzer = substr($analyzer, 8, -4);
    $analyzer = str_replace('/', '\\', $analyzer);
    if (strpos($analyzer, "\\Common\\" ) !== false) {
        continue;
    }
    $x = new $analyzer(null);
    if ( $x->getDescription() == '' ) {
        print "$analyzer has no human version\n";
    } else {
        $documented++;
    }
}

print "\n\n";
print count($analyzers)." analyzers\n";
print "$documented analyzers are documented ( ".number_format($documented / count($analyzers) * 100, 2)." % )\n";


?>