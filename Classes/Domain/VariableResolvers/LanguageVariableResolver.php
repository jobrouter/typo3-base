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
use JobRouter\AddOn\Typo3Base\Event\ResolveFinisherVariableEvent;
use JobRouter\AddOn\Typo3Base\Exception\VariableResolverException;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;

/**
 * @internal
 */
final class LanguageVariableResolver
{
    /**
     * @see https://regex101.com/r/BKiLTa/1
     */
    private const LANGUAGE_VARIABLE_REGEX = '/{__language\.(\w+)(\.(\w+))?}/';

    /**
     * @var string[]
     */
    private array $validLanguageVariables = [
        'base',
        'flagIdentifier',
        'hreflang',
        'languageId',
        'locale',
        'navigationTitle',
        'title',
        'typo3Language',
    ];

    public function __invoke(ResolveFinisherVariableEvent $event): void
    {
        $value = (string)$event->getValue();

        if (! \str_contains($value, '{__language.')) {
            return;
        }

        $this->checkValidFieldTypes($event);

        /** @var SiteLanguage|null $language */
        $language = $event->getRequest()->getAttribute('language');
        if ($language === null) {
            return;
        }

        if (! \preg_match_all(self::LANGUAGE_VARIABLE_REGEX, $value, $matches)) {
            return;
        }

        foreach ($matches[1] as $index => $match) {
            if (! \in_array($match, $this->validLanguageVariables, true)) {
                continue;
            }

            if ($match === 'locale' && ($matches[3][$index] ?? false) !== '') {
                $methodToCall = 'get' . \ucfirst($matches[3][$index]);
                $value = \str_replace($matches[0][$index], (string)$language->getLocale()->{$methodToCall}(), $value);
            } else {
                $methodToCall = 'get' . \ucfirst($match);
                $value = \str_replace($matches[0][$index], (string)$language->{$methodToCall}(), $value);
            }
        }

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
            1582654966,
        );
    }
}
