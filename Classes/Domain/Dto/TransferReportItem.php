<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_base" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace JobRouter\AddOn\Typo3Base\Domain\Dto;

/**
 * @internal Only for usage in the TYPO3 JobRouter extensions!
 */
final readonly class TransferReportItem
{
    public function __construct(
        public int $creationDate,
        public string $message,
        public string $correlationId,
    ) {}
}
