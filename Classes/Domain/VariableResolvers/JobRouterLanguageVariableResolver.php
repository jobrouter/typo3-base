<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_base" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace JobRouter\AddOn\Typo3Base\Domain\VariableResolvers;

use JobRouter\AddOn\Typo3Base\Enumeration\FieldType;
use JobRouter\AddOn\Typo3Base\Enumeration\JobRouterLanguages;
use JobRouter\AddOn\Typo3Base\Event\ResolveFinisherVariableEvent;
use JobRouter\AddOn\Typo3Base\Exception\VariableResolverException;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;

/**
 * @internal
 */
final class JobRouterLanguageVariableResolver
{
    private const VARIABLE_TO_RESOLVE = '{__jobRouterLanguage}';

    public function __invoke(ResolveFinisherVariableEvent $event): void
    {
        $value = (string)$event->getValue();

        if (! \str_contains($value, self::VARIABLE_TO_RESOLVE)) {
            return;
        }

        $this->checkValidFieldTypes($event);

        /** @var SiteLanguage|null $language */
        $language = $event->getRequest()->getAttribute('language');
        $languageCode = $language?->getLocale()?->getLanguageCode() ?? '';
        $jobRouterLanguage = JobRouterLanguages::tryFrom($languageCode)->name ?? '';
        $value = \str_replace(self::VARIABLE_TO_RESOLVE, $jobRouterLanguage, $value);

        $event->setValue($value);
    }

    private function checkValidFieldTypes(ResolveFinisherVariableEvent $event): void
    {
        if ($event->getFieldType() === FieldType::Text) {
            return;
        }

        throw new VariableResolverException(
            \sprintf(
                'The value "%s" contains a variable which can only be used in "Text" fields, type "%s" used',
                $event->getValue(),
                $event->getFieldType()->name,
            ),
            1594214444,
        );
    }
}
