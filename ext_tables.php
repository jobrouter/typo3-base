<?php
defined('TYPO3') || die();

(static function () {
    if ((new TYPO3\CMS\Core\Information\Typo3Version())->getMajorVersion() === 10) {
        // Since TYPO3 v11.4 icons can be registered in Configuration/Icons.php
        /** @var \TYPO3\CMS\Core\Imaging\IconRegistry $iconRegistry */
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
    }
})();
