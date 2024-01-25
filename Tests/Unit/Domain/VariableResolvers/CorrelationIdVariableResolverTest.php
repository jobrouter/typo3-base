<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_base" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace JobRouter\AddOn\Typo3Base\Tests\Unit\Domain\VariableResolvers;

use JobRouter\AddOn\Typo3Base\Domain\VariableResolvers\CorrelationIdVariableResolver;
use JobRouter\AddOn\Typo3Base\Enumeration\FieldType;
use JobRouter\AddOn\Typo3Base\Event\ResolveFinisherVariableEvent;
use JobRouter\AddOn\Typo3Base\Exception\VariableResolverException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

final class CorrelationIdVariableResolverTest extends TestCase
{
    private CorrelationIdVariableResolver $subject;
    private ServerRequestInterface & Stub $serverRequestStub;

    protected function setUp(): void
    {
        $this->serverRequestStub = $this->createStub(ServerRequestInterface::class);
        $this->subject = new CorrelationIdVariableResolver();
    }

    #[Test]
    #[DataProvider('dataProviderForResolveVariables')]
    public function resolveVariableCorrectly(string $value, string $correlationId, string $expected): void
    {
        $event = new ResolveFinisherVariableEvent(
            FieldType::Text,
            $value,
            $correlationId,
            [],
            $this->serverRequestStub,
        );

        $this->subject->__invoke($event);

        self::assertSame($expected, $event->getValue());
    }

    public static function dataProviderForResolveVariables(): \Generator
    {
        yield 'value with variable as only text' => [
            '{__correlationId}',
            'some-identifier',
            'some-identifier',
        ];

        yield 'value as text with variable among other text' => [
            'foo {__correlationId} bar',
            'some-identifier',
            'foo some-identifier bar',
        ];

        yield 'value as text with no variable' => [
            'foo bar',
            'some-identifier',
            'foo bar',
        ];

        yield 'value as text with another variable' => [
            '{__correlationId1}',
            'some-identifier',
            '{__correlationId1}',
        ];
    }

    #[Test]
    public function resolveThrowsExceptionWithFieldTypeNotString(): void
    {
        $this->expectException(VariableResolverException::class);
        $this->expectExceptionCode(1582654966);
        $this->expectExceptionMessage('The "{__correlationId}" variable can only be used in "Text" fields, type "Integer" used');

        $event = new ResolveFinisherVariableEvent(
            FieldType::Integer,
            '{__correlationId}',
            'some-identifier',
            [],
            $this->serverRequestStub,
        );

        $this->subject->__invoke($event);
    }
}
