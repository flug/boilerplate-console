<?php

declare(strict_types=1);

use PedroTroller\CS\Fixer\Fixers;
use PedroTroller\CS\Fixer\RuleSetFactory;

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules(
        RuleSetFactory::create()
            ->phpCsFixer(true)
            ->php(7.2, true)
            ->pedrotroller(true)
            ->enable('ordered_imports')
            ->enable('ordered_interfaces')
            ->enable('align_multiline_comment')
            ->enable('array_indentation')
            ->enable('no_superfluous_phpdoc_tags')
            ->getRules()
    )
    ->setUsingCache(false)
    ->registerCustomFixers(new Fixers())
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__)
            ->append([__FILE__])
    )
;
