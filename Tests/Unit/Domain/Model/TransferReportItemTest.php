<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_base" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterBase\Tests\Unit\Domain\Model;

use Brotkrueml\JobRouterBase\Domain\Model\TransferReportItem;
use PHPUnit\Framework\TestCase;

class TransferReportItemTest extends TestCase
{
    /**
     * @test
     */
    public function getterReturnCorrectValues(): void
    {
        $subject = new TransferReportItem(1615048018, 'some message', 'some correlation id');

        self::assertSame(1615048018, $subject->getCreationDate());
        self::assertSame('some message', $subject->getMessage());
        self::assertSame('some correlation id', $subject->getCorrelationId());
    }
}
