<?php
try {
    $a = new Phar('/chemin/vers/phar.phar');

    $a->addFile('/chemin/complet/vers/fichier');
    // démontre comment le fichier est stocké
    $b = $a['chemin/complet/vers/fichier']->getContent();

    $a->addFile('/chemin/complet/vers/fichier', 'mon/fichier.txt');
    $c = $a['mon/fichier.txt']->getContent();

    // démontre l'utilisation d'URL
    $a->addFile('http://www.exemple.com', 'exemple.html');
} catch (Exception $e) {
    // traite les erreurs ici
}
?>