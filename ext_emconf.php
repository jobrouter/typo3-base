<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'JobRouter Base',
    'description' => 'Base library for the TYPO3 JobRouter® extensions',
    'category' => 'misc',
    'author' => 'Chris Müller',
    'author_email' => 'typo3@krue.ml',
    'state' => 'stable',
    'version' => '1.0.0-dev',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.11-10.4.99',
        ],
        'conflicts' => [],
        'suggests' => [
            'jobrouter_data' => '0.12.0-0.12.99',
            'jobrouter_process' => '0.5.0-1.99.99',
        ],
    ],
    'autoload' => [
        'psr-4' => ['Brotkrueml\\JobRouterProcess\\' => 'Classes']
    ],
];
