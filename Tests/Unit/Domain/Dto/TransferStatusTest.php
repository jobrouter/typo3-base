<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_base" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace JobRouter\AddOn\Typo3Base\Tests\Unit\Domain\Dto;

use JobRouter\AddOn\Typo3Base\Domain\Dto\TransferStatus;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class TransferStatusTest extends TestCase
{
    private TransferStatus $subject;

    protected function setUp(): void
    {
        $this->subject = new TransferStatus();
    }

    #[Test]
    public function statusIsInitialisedCorrectly(): void
    {
        self::assertSame(0, $this->subject->getFailedCount());
        self::assertSame(0, $this->subject->getPendingCount());
        self::assertSame(0, $this->subject->getSuccessfulCount());
        self::assertSame(0, $this->subject->getNumberOfDays());
        self::assertNull($this->subject->getLastRun());
    }

    #[Test]
    public function setAndGetFailedCount(): void
    {
        $this->subject->setFailedCount(42);

        self::assertSame(42, $this->subject->getFailedCount());
    }

    #[Test]
    public function setAndGetPendingCount(): void
    {
        $this->subject->setPendingCount(23);

        self::assertSame(23, $this->subject->getPendingCount());
    }

    #[Test]
    public function setAndGetSuccessfulCount(): void
    {
        $this->subject->setSuccessfulCount(12);

        self::assertSame(12, $this->subject->getSuccessfulCount());
    }

    #[Test]
    public function setAndGetNumberOfDays(): void
    {
        $this->subject->setNumberOfDays(123);

        self::assertSame(123, $this->subject->getNumberOfDays());
    }

    #[Test]
    public function setAndGetLastRun(): void
    {
        $date = new \DateTimeImmutable('2020-09-01 16:00:00');
        $this->subject->setLastRun($date);

        self::assertSame($date, $this->subject->getLastRun());
    }
}
