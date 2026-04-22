<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'JobRouter Base',
    'description' => 'Base extension for the TYPO3 JobRouter® extensions',
    'category' => 'misc',
    'author' => 'Chris Müller',
    'author_company' => 'JobRouter GmbH',
    'state' => 'stable',
    'version' => '5.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.0-14.3.99',
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
