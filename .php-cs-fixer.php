<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('docker')
    ->exclude('docs')
    ->exclude('log')
    ->exclude('node_modules')
    ->exclude('var')
    ->exclude('vendor')
;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@DoctrineAnnotation' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@PhpCsFixer' => true,
        '@PhpCsFixer:risky' => true,
        'concat_space' => ['spacing' => 'one'],
        'yoda_style' => false,
    ])
    ->setFinder($finder)
;
