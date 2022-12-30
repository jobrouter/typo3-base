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
use Brotkrueml\JobRouterBase\Event\ResolveFinisherVariableEvent;
use Brotkrueml\JobRouterBase\Exception\VariableResolverException;

/**
 * @internal
 */
final class LanguageVariableResolver
{
    /**
     * @see https://regex101.com/r/9HJ3lr/1
     */
    private const LANGUAGE_VARIABLE_REGEX = '/{__language\.(\w+)}/';

    /**
     * @var string[]
     */
    private array $validLanguageVariables = [
        'base',
        'direction',
        'flagIdentifier',
        'hreflang',
        'languageId',
        'locale',
        'navigationTitle',
        'title',
        'twoLetterIsoCode',
        'typo3Language',
    ];

    public function __invoke(ResolveFinisherVariableEvent $event): void
    {
        $value = (string)$event->getValue();

        if (! \str_contains($value, '{__language.')) {
            return;
        }

        $this->checkValidFieldTypes($event);

        $language = $event->getRequest()->getAttribute('language', null);
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

            $methodToCall = 'get' . \ucfirst($match);
            $value = \str_replace($matches[0][$index], (string)$language->{$methodToCall}(), $value);
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
                $event->getFieldType()->name
            ),
            1582654966
        );
    }
}
