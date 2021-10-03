<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_base" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

return [
    'jobrouter-base-status-failed' => [
        'provider' => TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:' . Brotkrueml\JobRouterBase\Extension::KEY . '/Resources/Public/Icons/status-failed.svg',
    ],
    'jobrouter-base-status-pending' => [
        'provider' => TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:' . Brotkrueml\JobRouterBase\Extension::KEY . '/Resources/Public/Icons/status-pending.svg',
    ],
    'jobrouter-base-status-successful' => [
        'provider' => TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:' . Brotkrueml\JobRouterBase\Extension::KEY . '/Resources/Public/Icons/status-successful.svg',
    ],
];
