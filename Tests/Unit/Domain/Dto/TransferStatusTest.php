<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_base" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterBase\Tests\Unit\Domain\Dto;

use Brotkrueml\JobRouterBase\Domain\Dto\TransferStatus;
use PHPUnit\Framework\TestCase;

class TransferStatusTest extends TestCase
{
    private TransferStatus $subject;

    protected function setUp(): void
    {
        $this->subject = new TransferStatus();
    }

    /**
     * @test
     */
    public function statusIsInitialisedCorrectly(): void
    {
        self::assertSame(0, $this->subject->getFailedCount());
        self::assertSame(0, $this->subject->getPendingCount());
        self::assertSame(0, $this->subject->getSuccessfulCount());
        self::assertSame(0, $this->subject->getNumberOfDays());
        self::assertNull($this->subject->getLastRun());
    }

    /**
     * @test
     */
    public function setAndGetFailedCount(): void
    {
        $this->subject->setFailedCount(42);

        self::assertSame(42, $this->subject->getFailedCount());
    }

    /**
     * @test
     */
    public function setAndGetPendingCount(): void
    {
        $this->subject->setPendingCount(23);

        self::assertSame(23, $this->subject->getPendingCount());
    }

    /**
     * @test
     */
    public function setAndGetSuccessfulCount(): void
    {
        $this->subject->setSuccessfulCount(12);

        self::assertSame(12, $this->subject->getSuccessfulCount());
    }

    /**
     * @test
     */
    public function setAndGetNumberOfDays(): void
    {
        $this->subject->setNumberOfDays(123);

        self::assertSame(123, $this->subject->getNumberOfDays());
    }

    /**
     * @test
     */
    public function setAndGetLastRun(): void
    {
        $date = new \DateTimeImmutable('2020-09-01 16:00:00');
        $this->subject->setLastRun($date);

        self::assertSame($date, $this->subject->getLastRun());
    }
}
