<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'JobRouter Base',
    'description' => 'Base library for the TYPO3 JobRouter® extensions',
    'category' => 'misc',
    'author' => 'Chris Müller',
    'author_email' => 'typo3@krue.ml',
    'state' => 'beta',
    'version' => '0.1.0',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-10.4.99',
        ],
        'conflicts' => [],
        'suggests' => [
            'jobrouter_data' => '0.12.0-0.12.99',
            'jobrouter_process' => '0.5.0-0.5.99',
        ],
    ],
    'autoload' => [
        'psr-4' => ['Brotkrueml\\JobRouterProcess\\' => 'Classes']
    ],
];
