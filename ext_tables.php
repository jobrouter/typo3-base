<?php
defined('TYPO3_MODE') || die('Access denied.');

(function () {
    $iconRegistry = TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
        TYPO3\CMS\Core\Imaging\IconRegistry::class
    );
    foreach (['failed', 'pending', 'successful'] as $status) {
        $iconRegistry->registerIcon(
            'jobrouter-base-status-' . $status,
            TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            [
                'source' => \sprintf(
                    'EXT:%s/Resources/Public/Icons/status-%s.svg',
                    Brotkrueml\JobRouterBase\Extension::KEY,
                    $status
                )
            ]
        );
    }
})();
