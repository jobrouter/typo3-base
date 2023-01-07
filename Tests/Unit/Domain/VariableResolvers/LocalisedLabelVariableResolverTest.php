<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_base" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterBase\Tests\Unit\Domain\VariableResolvers;

use Brotkrueml\JobRouterBase\Domain\VariableResolvers\LocalisedLabelVariableResolver;
use Brotkrueml\JobRouterBase\Enumeration\FieldType;
use Brotkrueml\JobRouterBase\Event\ResolveFinisherVariableEvent;
use Brotkrueml\JobRouterBase\Exception\VariableResolverException;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;

class LocalisedLabelVariableResolverTest extends TestCase
{
    private LocalisedLabelVariableResolver $subject;
    private ServerRequestInterface & Stub $requestStub;
    private LanguageService & Stub $languageServiceStub;

    protected function setUp(): void
    {
        $this->languageServiceStub = $this->createStub(LanguageService::class);
        $languageServiceFactoryStub = $this->createStub(LanguageServiceFactory::class);
        $languageServiceFactoryStub
            ->method('createFromSiteLanguage')
            ->willReturn($this->languageServiceStub);
        $this->subject = new LocalisedLabelVariableResolver($languageServiceFactoryStub);

        $this->requestStub = $this->createStub(ServerRequestInterface::class);
        $this->requestStub
            ->method('getAttribute')
            ->with('language')
            ->willReturn($this->createStub(SiteLanguage::class));
    }

    /**
     * @test
     */
    public function oneLocalisedLabelIsResolved(): void
    {
        $this->languageServiceStub
            ->method('sL')
            ->with('LLL:EXT:some_ext/Resources/Private/Language/locallang.xlf:some.label')
            ->willReturn('localised some label');

        $event = new ResolveFinisherVariableEvent(
            FieldType::Text,
            'foo {__LLL:EXT:some_ext/Resources/Private/Language/locallang.xlf:some.label} bar',
            '',
            [],
            $this->requestStub,
        );

        $this->subject->__invoke($event);

        self::assertSame('foo localised some label bar', $event->getValue());
    }

    /**
     * @test
     */
    public function twoLocalisedLabelAreResolved(): void
    {
        $translationMap = [
            [
                'LLL:EXT:some_ext/Resources/Private/Language/locallang.xlf:some.label',
                'localised some label',
            ],
            [
                'LLL:EXT:some_ext/Resources/Private/Language/locallang.xlf:another.label',
                'localised another label',
            ],
        ];

        $this->languageServiceStub
            ->method('sL')
            ->willReturnMap($translationMap);

        $event = new ResolveFinisherVariableEvent(
            FieldType::Text,
            'foo {__LLL:EXT:some_ext/Resources/Private/Language/locallang.xlf:some.label} bar {__LLL:EXT:some_ext/Resources/Private/Language/locallang.xlf:another.label}',
            '',
            [],
            $this->requestStub,
        );

        $this->subject->__invoke($event);

        self::assertSame('foo localised some label bar localised another label', $event->getValue());
    }

    /**
     * @test
     */
    public function noLocalisedLabelFoundThenValueIsUntouched(): void
    {
        $event = new ResolveFinisherVariableEvent(
            FieldType::Text,
            'foo bar',
            '',
            [],
            $this->requestStub,
        );

        $this->subject->__invoke($event);

        self::assertSame(
            'foo bar',
            $event->getValue(),
        );
    }

    /**
     * @test
     */
    public function localisedLabelIsNotFoundThenValueIsUntouched(): void
    {
        $this->languageServiceStub
            ->method('sL')
            ->willReturn('');

        $event = new ResolveFinisherVariableEvent(
            FieldType::Text,
            'foo {__LLL:EXT:some_ext/Resources/Private/Language/locallang.xlf:not.existing} bar',
            '',
            [],
            $this->requestStub,
        );

        $this->subject->__invoke($event);

        self::assertSame(
            'foo {__LLL:EXT:some_ext/Resources/Private/Language/locallang.xlf:not.existing} bar',
            $event->getValue(),
        );
    }

    /**
     * @test
     */
    public function wrongVariableDescriptionThenValueIsUntouched(): void
    {
        $event = new ResolveFinisherVariableEvent(
            FieldType::Text,
            'foo {__LLL:EXT:some_ext/Resources/Private/Language/locallang.xlf:not.existing bar',
            '',
            [],
            $this->requestStub,
        );

        $this->subject->__invoke($event);

        self::assertSame(
            'foo {__LLL:EXT:some_ext/Resources/Private/Language/locallang.xlf:not.existing bar',
            $event->getValue(),
        );
    }

    /**
     * @test
     */
    public function resolveThrowsExceptionWithFieldTypeNotString(): void
    {
        $this->expectException(VariableResolverException::class);
        $this->expectExceptionCode(1582907006);
        $this->expectExceptionMessage('The value "{__LLL:EXT:some_ext/Resources/Private/Language/locallang.xlf:some.label}" contains a localised label which can only be used in "Text" fields, type "Integer" used');

        $event = new ResolveFinisherVariableEvent(
            FieldType::Integer,
            '{__LLL:EXT:some_ext/Resources/Private/Language/locallang.xlf:some.label}',
            '',
            [],
            $this->requestStub,
        );

        $this->subject->__invoke($event);
    }
}
