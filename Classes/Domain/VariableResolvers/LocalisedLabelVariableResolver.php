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
use Brotkrueml\JobRouterBase\Language\TranslationService;

/**
 * @internal
 */
final class LocalisedLabelVariableResolver
{
    /**
     * @var TranslationService
     */
    private $translationService;

    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    public function __invoke(ResolveFinisherVariableEvent $event): void
    {
        $value = $event->getValue();

        if (! \str_contains($value, '{__LLL:')) {
            return;
        }

        $this->checkValidFieldTypes($event);

        if (! \preg_match_all('/{__(LLL:.+?)}/', $value, $matches)) {
            return;
        }

        foreach ($matches[1] as $index => $match) {
            $translation = $this->translationService->translate($match);

            if ($translation !== null) {
                $value = \str_replace($matches[0][$index], $translation, $value);
            }
        }

        $event->setValue($value);
    }

    private function checkValidFieldTypes(ResolveFinisherVariableEvent $event): void
    {
        if ($event->getFieldType() === FieldTypeEnumeration::TEXT) {
            return;
        }

        throw new VariableResolverException(
            \sprintf(
                'The value "%s" contains a localised label which can only be used in Text fields ("%d"), type "%d" used',
                $event->getValue(),
                FieldTypeEnumeration::TEXT,
                $event->getFieldType()
            ),
            1582907006
        );
    }
}
