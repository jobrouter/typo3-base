<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_base" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterBase\Domain\Model;

/**
 * @internal Only for usage in the TYPO3 JobRouter extensions!
 */
final class TransferStatus
{
    private int $failedCount = 0;
    private int $pendingCount = 0;
    private int $successfulCount = 0;
    private int $numberOfDays = 0;
    private ?\DateTimeInterface $lastRun = null;

    public function setFailedCount(int $count): void
    {
        $this->failedCount = $count;
    }

    public function getFailedCount(): int
    {
        return $this->failedCount;
    }

    public function setPendingCount(int $count): void
    {
        $this->pendingCount = $count;
    }

    public function getPendingCount(): int
    {
        return $this->pendingCount;
    }

    public function setSuccessfulCount(int $count): void
    {
        $this->successfulCount = $count;
    }

    public function getSuccessfulCount(): int
    {
        return $this->successfulCount;
    }

    public function getLastRun(): ?\DateTimeInterface
    {
        return $this->lastRun;
    }

    public function setLastRun(\DateTimeInterface $lastRun): void
    {
        $this->lastRun = $lastRun;
    }

    public function setNumberOfDays(int $numberOfDays): void
    {
        $this->numberOfDays = $numberOfDays;
    }

    public function getNumberOfDays(): int
    {
        return $this->numberOfDays;
    }
}
