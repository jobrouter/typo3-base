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
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;

/**
 * @internal
 */
final class LocalisedLabelVariableResolver
{
    /**
     * @see https://regex101.com/r/C8VekG/1/
     */
    private const LOCALISED_STRING_REGEX = '/{__(LLL:.+?)}/';

    public function __construct(
        private readonly LanguageServiceFactory $languageServiceFactory,
    ) {
    }

    public function __invoke(ResolveFinisherVariableEvent $event): void
    {
        $value = (string)$event->getValue();

        if (! \str_contains($value, '{__LLL:')) {
            return;
        }

        $this->checkValidFieldTypes($event);

        if (! \preg_match_all(self::LOCALISED_STRING_REGEX, $value, $matches)) {
            return;
        }

        /** @var SiteLanguage $siteLanguage */
        $siteLanguage = $event->getRequest()->getAttribute('language');
        $languageService = $this->languageServiceFactory->createFromSiteLanguage($siteLanguage);
        foreach ($matches[1] as $index => $match) {
            $translation = $languageService->sL($match);

            if ($translation !== '') {
                $value = \str_replace($matches[0][$index], $translation, $value);
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
                'The value "%s" contains a localised label which can only be used in "Text" fields, type "%s" used',
                $event->getValue(),
                $event->getFieldType()->name,
            ),
            1582907006,
        );
    }
}
