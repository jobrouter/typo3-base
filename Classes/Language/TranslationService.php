<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_base" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterBase\Language;

use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * @internal
 */
class TranslationService
{
    public function translate(string $key): ?string
    {
        return LocalizationUtility::translate($key);
    }
}
