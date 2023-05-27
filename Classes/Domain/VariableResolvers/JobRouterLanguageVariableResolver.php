<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_base" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterBase\Domain\VariableResolvers;

use Brotkrueml\JobRouterBase\Enumeration\FieldType;
use Brotkrueml\JobRouterBase\Enumeration\JobRouterLanguages;
use Brotkrueml\JobRouterBase\Event\ResolveFinisherVariableEvent;
use Brotkrueml\JobRouterBase\Exception\VariableResolverException;
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
        $languageIsoCode = $language instanceof SiteLanguage ? $language->getTwoLetterIsoCode() : '';
        $jobRouterLanguage = JobRouterLanguages::tryFrom($languageIsoCode)->name ?? '';
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
