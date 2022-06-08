<?php

/*
 * This file is part of the Flowmailer Mailer Bridge for Symfony package.
 * (c) Flowmailer.
 */

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony'             => true,
        'declare_strict_types' => true,
        'ordered_imports'      => true,
        'psr_autoloading'      => false,
        'yoda_style'           => false,
        'phpdoc_order'         => true,
        'array_syntax'         => [
            'syntax' => 'short',
        ],
        'binary_operator_spaces' => [
            'operators' => [
                '='  => 'align_single_space',
                '=>' => 'align_single_space',
            ],
        ],
        'header_comment' => [
            'header' => <<<EOH
This file is part of the Flowmailer Mailer Bridge for Symfony package.
(c) Flowmailer.
EOH
            ,
        ],
    ])
    ->setFinder($finder)
    ->setCacheFile('.php-cs-fixer.cache') // forward compatibility with 3.x line
;
