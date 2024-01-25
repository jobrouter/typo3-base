<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_base" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace JobRouter\AddOn\Typo3Base\Tests\Unit\Domain\VariableResolvers;

use JobRouter\AddOn\Typo3Base\Domain\VariableResolvers\VariableResolver;
use JobRouter\AddOn\Typo3Base\Enumeration\FieldType;
use JobRouter\AddOn\Typo3Base\Event\ResolveFinisherVariableEvent;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ServerRequestInterface;

final class VariableResolverTest extends TestCase
{
    private ServerRequestInterface & Stub $requestStub;
    /**
     * @var MockObject&EventDispatcherInterface
     */
    private MockObject $eventDispatcherMock;
    private VariableResolver $subject;

    protected function setUp(): void
    {
        $this->requestStub = $this->createStub(ServerRequestInterface::class);
        $this->eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);
        $this->subject = new VariableResolver($this->eventDispatcherMock);
        $this->subject->setCorrelationId('some identifier');
        $this->subject->setFormValues([
            'foo' => 'bar',
        ]);
        $this->subject->setRequest($this->requestStub);
    }

    #[Test]
    public function resolveReturnsValueUntouchedIfNotContainingVariable(): void
    {
        $this->eventDispatcherMock
            ->expects(self::never())
            ->method('dispatch');

        $actual = $this->subject->resolve(FieldType::Text, 'value without variable');

        self::assertSame('value without variable', $actual);
    }

    #[Test]
    public function resolveCallsEventDispatcherIfVariableIsAvailable(): void
    {
        $returnedEvent = new ResolveFinisherVariableEvent(
            FieldType::Text,
            'resolved value',
            'some identifier',
            [
                'foo' => 'bar',
            ],
            $this->requestStub,
        );

        $this->eventDispatcherMock
            ->expects(self::once())
            ->method('dispatch')
            ->willReturn($returnedEvent);

        $actual = $this->subject->resolve(FieldType::Text, '{__variable} value');

        self::assertSame('resolved value', $actual);
    }
}
