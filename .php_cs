<?php
$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->files()
    ->in('library/Tokenizer')
    ->in('library/Analyzer')
    ->in('library/Tasks')
    ->name('*.php');

return Symfony\CS\Config\Config::create()
    ->level(\Symfony\CS\FixerInterface::NONE_LEVEL)
    ->fixers(
        array(
        'encoding',
        'eof_ending',
        'elseif',
        'trailing_spaces',
        'indentation'
        )
    )
    ->finder($finder);
