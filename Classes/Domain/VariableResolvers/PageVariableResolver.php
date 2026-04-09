<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_base" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace JobRouter\AddOn\Typo3Base\Domain\VariableResolvers;

use JobRouter\AddOn\Typo3Base\Event\ResolveFinisherVariableEvent;
use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Frontend\Page\PageInformation;

/**
 * @internal
 */
#[AsEventListener(
    identifier: 'jobrouter-base/page-variable-resolver',
)]
final readonly class PageVariableResolver
{
    /**
     * @see https://regex101.com/r/V7K9WB/1
     */
    private const VARIABLE_REGEX = '/{__page\.(\w+)}/';

    public function __invoke(ResolveFinisherVariableEvent $event): void
    {
        $value = (string) $event->getValue();

        if (! \str_contains($value, '{__page.')) {
            return;
        }

        if (! \preg_match_all(self::VARIABLE_REGEX, $value, $matches)) {
            return;
        }

        /** @var PageInformation|null $pageInformation */
        $pageInformation = $event->getRequest()->getAttribute('frontend.page.information');
        if ($pageInformation === null) {
            return;
        }

        $pageRecord = $pageInformation->getPageRecord();

        foreach ($matches[1] as $index => $propertyName) {
            if (! \array_key_exists($propertyName, $pageRecord)) {
                continue;
            }

            $value = \str_replace($matches[0][$index], (string) $pageRecord[$propertyName], $value);
        }

        $event->setValue($value);
    }
}
