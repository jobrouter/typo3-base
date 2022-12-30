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
use Brotkrueml\JobRouterBase\Enumeration\FieldTypeEnumeration;
use Brotkrueml\JobRouterBase\Event\ResolveFinisherVariableEvent;
use Brotkrueml\JobRouterBase\Exception\VariableResolverException;
use Brotkrueml\JobRouterBase\Language\TranslationService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class LocalisedLabelVariableResolverTest extends TestCase
{
    private LocalisedLabelVariableResolver $subject;
    private TranslationService & MockObject $translationServiceMock;
    private ServerRequestInterface & Stub $serverRequestStub;

    protected function setUp(): void
    {
        $this->translationServiceMock = $this->createMock(TranslationService::class);
        $this->subject = new LocalisedLabelVariableResolver($this->translationServiceMock);
        $this->serverRequestStub = $this->createStub(ServerRequestInterface::class);
    }

    /**
     * @test
     */
    public function oneLocalisedLabelIsResolved(): void
    {
        $this->translationServiceMock
            ->expects(self::once())
            ->method('translate')
            ->with('LLL:EXT:some_ext/Resources/Private/Language/locallang.xlf:some.label')
            ->willReturn('localised some label');

        $event = new ResolveFinisherVariableEvent(
            FieldTypeEnumeration::TEXT,
            'foo {__LLL:EXT:some_ext/Resources/Private/Language/locallang.xlf:some.label} bar',
            '',
            [],
            $this->serverRequestStub
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

        $this->translationServiceMock
            ->expects(self::exactly(2))
            ->method('translate')
            ->willReturnMap($translationMap);

        $event = new ResolveFinisherVariableEvent(
            FieldTypeEnumeration::TEXT,
            'foo {__LLL:EXT:some_ext/Resources/Private/Language/locallang.xlf:some.label} bar {__LLL:EXT:some_ext/Resources/Private/Language/locallang.xlf:another.label}',
            '',
            [],
            $this->serverRequestStub
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
            FieldTypeEnumeration::TEXT,
            'foo bar',
            '',
            [],
            $this->serverRequestStub
        );

        $this->subject->__invoke($event);

        self::assertSame(
            'foo bar',
            $event->getValue()
        );
    }

    /**
     * @test
     */
    public function localisedLabelIsNotFoundThenValueIsUntouched(): void
    {
        $this->translationServiceMock
            ->expects(self::once())
            ->method('translate')
            ->willReturn(null);

        $event = new ResolveFinisherVariableEvent(
            FieldTypeEnumeration::TEXT,
            'foo {__LLL:EXT:some_ext/Resources/Private/Language/locallang.xlf:not.existing} bar',
            '',
            [],
            $this->serverRequestStub
        );

        $this->subject->__invoke($event);

        self::assertSame(
            'foo {__LLL:EXT:some_ext/Resources/Private/Language/locallang.xlf:not.existing} bar',
            $event->getValue()
        );
    }

    /**
     * @test
     */
    public function localisedLabelIsFoundButValueIsEmpty(): void
    {
        $this->translationServiceMock
            ->expects(self::once())
            ->method('translate')
            ->willReturn('');

        $event = new ResolveFinisherVariableEvent(
            FieldTypeEnumeration::TEXT,
            'foo {__LLL:EXT:some_ext/Resources/Private/Language/locallang.xlf:empty} bar',
            '',
            [],
            $this->serverRequestStub
        );

        $this->subject->__invoke($event);

        self::assertSame(
            'foo  bar',
            $event->getValue()
        );
    }

    /**
     * @test
     */
    public function wrongVariableDescriptionThenValueIsUntouched(): void
    {
        $event = new ResolveFinisherVariableEvent(
            FieldTypeEnumeration::TEXT,
            'foo {__LLL:EXT:some_ext/Resources/Private/Language/locallang.xlf:not.existing bar',
            '',
            [],
            $this->serverRequestStub
        );

        $this->subject->__invoke($event);

        self::assertSame(
            'foo {__LLL:EXT:some_ext/Resources/Private/Language/locallang.xlf:not.existing bar',
            $event->getValue()
        );
    }

    /**
     * @test
     */
    public function resolveThrowsExceptionWithFieldTypeNotString(): void
    {
        $this->expectException(VariableResolverException::class);
        $this->expectExceptionCode(1582907006);
        $this->expectExceptionMessage('The value "{__LLL:EXT:some_ext/Resources/Private/Language/locallang.xlf:some.label}" contains a localised label which can only be used in Text fields ("1"), type "2" used');

        $event = new ResolveFinisherVariableEvent(
            FieldTypeEnumeration::INTEGER,
            '{__LLL:EXT:some_ext/Resources/Private/Language/locallang.xlf:some.label}',
            '',
            [],
            $this->serverRequestStub
        );

        $this->subject->__invoke($event);
    }
}
