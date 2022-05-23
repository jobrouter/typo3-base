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
final class TransferReportItem
{
    private int $creationDate;
    private string $message;
    private string $correlationId;

    public function __construct(int $creationDate, string $message, string $correlationId)
    {
        $this->creationDate = $creationDate;
        $this->message = $message;
        $this->correlationId = $correlationId;
    }

    public function getCreationDate(): int
    {
        return $this->creationDate;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getCorrelationId(): string
    {
        return $this->correlationId;
    }
}
