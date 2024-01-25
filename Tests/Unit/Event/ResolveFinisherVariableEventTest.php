<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_base" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace JobRouter\AddOn\Typo3Base\Tests\Unit\Event;

use JobRouter\AddOn\Typo3Base\Enumeration\FieldType;
use JobRouter\AddOn\Typo3Base\Event\ResolveFinisherVariableEvent;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

final class ResolveFinisherVariableEventTest extends TestCase
{
    private ServerRequestInterface & Stub $serverRequestStub;

    protected function setUp(): void
    {
        $this->serverRequestStub = $this->createStub(ServerRequestInterface::class);
    }

    #[Test]
    public function gettersReturnValuesCorrectly(): void
    {
        $subject = new ResolveFinisherVariableEvent(
            FieldType::Integer,
            'some-value',
            'some-identifier',
            [
                'formName' => 'formValue',
            ],
            $this->serverRequestStub,
        );

        self::assertSame(FieldType::Integer, $subject->getFieldType());
        self::assertSame('some-value', $subject->getValue());
        self::assertSame('some-identifier', $subject->getCorrelationId());
        self::assertSame([
            'formName' => 'formValue',
        ], $subject->getFormValues());
        self::assertSame($this->serverRequestStub, $subject->getRequest());
    }

    #[Test]
    public function setValueSetsTheValueCorrectly(): void
    {
        $subject = new ResolveFinisherVariableEvent(
            FieldType::Text,
            'some-value',
            'some-identifier',
            [],
            $this->serverRequestStub,
        );

        $subject->setValue('some-other-value');

        self::assertSame('some-other-value', $subject->getValue());
    }
}
