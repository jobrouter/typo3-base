<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_base" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterBase\Domain\VariableResolvers;

use Brotkrueml\JobRouterBase\Event\ResolveFinisherVariableEvent;

/**
 * @internal
 */
final class PageVariableResolver
{
    /**
     * @see https://regex101.com/r/V7K9WB/1
     */
    private const VARIABLE_REGEX = '/{__page\.(\w+)}/';

    public function __invoke(ResolveFinisherVariableEvent $event): void
    {
        $value = (string)$event->getValue();

        if (! \str_contains($value, '{__page.')) {
            return;
        }

        if (! \preg_match_all(self::VARIABLE_REGEX, $value, $matches)) {
            return;
        }

        $pageProperties = $this->getPageProperties();
        foreach ($matches[1] as $index => $propertyName) {
            if (! \array_key_exists($propertyName, $pageProperties)) {
                continue;
            }

            $value = \str_replace($matches[0][$index], (string)$pageProperties[$propertyName], $value);
        }

        $event->setValue($value);
    }

    /**
     * @return array<string, int|string|null>
     */
    private function getPageProperties(): array
    {
        return $GLOBALS['TSFE']->page;
    }
}
