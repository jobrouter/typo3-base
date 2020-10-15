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
 * @internal
 */
final class TransferStatus
{
    /**
     * @var int
     */
    private $failedCount = 0;

    /**
     * @var int
     */
    private $pendingCount = 0;

    /**
     * @var int
     */
    private $successfulCount = 0;

    /**
     * @var \DateTimeInterface
     */
    private $lastRun;

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
}
