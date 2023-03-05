<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_base" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterBase\Tests\Unit\Domain\VariableResolvers;

use Brotkrueml\JobRouterBase\Domain\VariableResolvers\JobRouterLanguageVariableResolver;
use Brotkrueml\JobRouterBase\Enumeration\FieldType;
use Brotkrueml\JobRouterBase\Event\ResolveFinisherVariableEvent;
use Brotkrueml\JobRouterBase\Exception\VariableResolverException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;

final class JobRouterLanguageVariableResolverTest extends TestCase
{
    private JobRouterLanguageVariableResolver $subject;
    private ServerRequestInterface & Stub $serverRequestStub;

    protected function setUp(): void
    {
        $this->subject = new JobRouterLanguageVariableResolver();

        /** @var Stub|UriInterface $baseStub */
        $baseStub = $this->createStub(UriInterface::class);
        $baseStub
            ->method('__toString')
            ->willReturn('https://www.example.org/');

        $this->serverRequestStub = $this->createStub(ServerRequestInterface::class);
    }

    #[Test]
    #[DataProvider('dataProvider')]
    public function jobRouterLanguageVariableIsResolvedCorrectly(string $value, string $isoCode, string $expected): void
    {
        $siteLanguage = new SiteLanguage(
            1,
            '',
            $this->createStub(UriInterface::class),
            [
                'iso-639-1' => $isoCode,
            ],
        );

        $this->serverRequestStub
            ->method('getAttribute')
            ->with('language')
            ->willReturn($siteLanguage);

        $event = new ResolveFinisherVariableEvent(
            FieldType::Text,
            $value,
            '',
            [],
            $this->serverRequestStub,
        );

        $this->subject->__invoke($event);

        self::assertSame($expected, $event->getValue());
    }

    public static function dataProvider(): iterable
    {
        yield 'jobRouterLanguage is resolved for a supported language' => [
            '{__jobRouterLanguage}',
            'fi',
            'finnish',
        ];

        yield 'jobRouterLanguage is resolved to empty string on non-supported language' => [
            '{__jobRouterLanguage}',
            'zz',
            '',
        ];

        yield 'jobRouterLanguage is resolved with prefix and postfix' => [
            'foo {__jobRouterLanguage} bar',
            'en',
            'foo english bar',
        ];

        yield 'jobRouterLanguage is resolved twice' => [
            'foo {__jobRouterLanguage} bar {__jobRouterLanguage} qux',
            'en',
            'foo english bar english qux',
        ];

        yield 'value is untouched when variable is not set' => [
            'foo bar',
            'en',
            'foo bar',
        ];
    }

    #[Test]
    public function wrongFieldTypeThrowsException(): void
    {
        $this->expectException(VariableResolverException::class);
        $this->expectExceptionCode(1594214444);
        $this->expectExceptionMessage('The value "{__jobRouterLanguage}" contains a variable which can only be used in "Text" fields, type "Integer" used');

        $event = new ResolveFinisherVariableEvent(
            FieldType::Integer,
            '{__jobRouterLanguage}',
            '',
            [],
            $this->serverRequestStub,
        );

        $this->subject->__invoke($event);
    }

    #[Test]
    public function languageCannotBeDeterminedThenVariableIsRemoved(): void
    {
        $this->serverRequestStub
            ->method('getAttribute')
            ->with('language')
            ->willReturn(null);

        $event = new ResolveFinisherVariableEvent(
            FieldType::Text,
            '{__jobRouterLanguage}',
            '',
            [],
            $this->serverRequestStub,
        );

        $this->subject->__invoke($event);

        self::assertSame('', $event->getValue());
    }
}
