<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_base" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterBase\Domain\VariableResolvers;

use Brotkrueml\JobRouterBase\Enumeration\FieldTypeEnumeration;
use Brotkrueml\JobRouterBase\Event\ResolveFinisherVariableEvent;
use Brotkrueml\JobRouterBase\Exception\VariableResolverException;

/**
 * @internal
 */
final class JobRouterLanguageVariableResolver
{
    private const VARIABLE_TO_RESOLVE = '{__jobRouterLanguage}';

    private const LANGUAGE_MAPPINGS = [
        'ar' => 'arabic',
        'cs' => 'czech',
        'da' => 'danish',
        'de' => 'german',
        'en' => 'english',
        'es' => 'spanish',
        'fi' => 'finnish',
        'fr' => 'french',
        'hu' => 'hungarian',
        'it' => 'italian',
        'ja' => 'japanese',
        'nl' => 'dutch',
        'pl' => 'polish',
        'ro' => 'romanian',
        'ru' => 'russian',
        'sk' => 'slovak',
        'sl' => 'slovenian',
        'tr' => 'turkish',
        'zh' => 'chinese',
    ];

    public function __invoke(ResolveFinisherVariableEvent $event): void
    {
        $value = $event->getValue();

        if (! \str_contains($value, self::VARIABLE_TO_RESOLVE)) {
            return;
        }

        $this->checkValidFieldTypes($event);

        $language = $event->getRequest()->getAttribute('language', null);
        $languageIsoCode = $language ? $language->getTwoLetterIsoCode() : '';
        $jobRouterLanguage = self::LANGUAGE_MAPPINGS[$languageIsoCode] ?? '';
        $value = \str_replace(self::VARIABLE_TO_RESOLVE, $jobRouterLanguage, $value);

        $event->setValue($value);
    }

    private function checkValidFieldTypes(ResolveFinisherVariableEvent $event): void
    {
        if ($event->getFieldType() === FieldTypeEnumeration::TEXT) {
            return;
        }

        throw new VariableResolverException(
            \sprintf(
                'The value "%s" contains a variable which can only be used in Text fields ("%d"), type "%d" used',
                $event->getValue(),
                FieldTypeEnumeration::TEXT,
                $event->getFieldType()
            ),
            1594214444
        );
    }
}
