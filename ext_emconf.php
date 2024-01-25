<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'JobRouter Base',
    'description' => 'Base extension for the TYPO3 JobRouter® extensions',
    'category' => 'misc',
    'author' => 'Chris Müller',
    'author_email' => 'typo3@krue.ml',
    'state' => 'stable',
    'version' => '3.0.0-dev',
    'constraints' => [
        'depends' => [
            'php' => '8.1.0-0.0.0',
            'typo3' => '11.5.3-12.4.99',
        ],
        'conflicts' => [],
        'suggests' => [
            'jobrouter_data' => '',
            'jobrouter_process' => '',
        ],
    ],
    'autoload' => [
        'psr-4' => ['JobRouter\AddOn\Typo3Base\\' => 'Classes']
    ],
];
