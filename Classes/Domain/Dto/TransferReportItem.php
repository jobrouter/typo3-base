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
final class TransferReportItem
{
    public function __construct(
        public readonly int $creationDate,
        public readonly string $message,
        public readonly string $correlationId,
    ) {}
}
