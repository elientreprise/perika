<?php

$finder = (new PhpCsFixer\Finder())
    ->in(['src', 'tests'])
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
    ])
    ->setFinder($finder)
;
