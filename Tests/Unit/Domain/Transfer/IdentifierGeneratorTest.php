<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_base" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterBase\Test\Unit\Domain\Transfer;

use Brotkrueml\JobRouterBase\Domain\Transfer\IdentifierGenerator;
use PHPUnit\Framework\TestCase;

class IdentifierGeneratorTest extends TestCase
{
    /** @var IdentifierGenerator */
    private $subject;

    protected function setUp(): void
    {
        $this->subject = new IdentifierGenerator();
    }

    /**
     * @test
     */
    public function buildReturnsSameIdentifierWhenCalledTwice(): void
    {
        $actual1 = $this->subject->build('some-key');
        $actual2 = $this->subject->build('some-key');

        self::assertStringStartsWith('some-key_', $actual1);
        self::assertSame($actual1, $actual2);
    }

    /**
     * @test
     */
    public function buildReturnsDifferentIdentifierWhenCalledTwice(): void
    {
        $actual1 = $this->subject->build('some-key');
        $actual2 = $this->subject->build('other-key');

        self::assertStringStartsWith('some-key_', $actual1);
        self::assertStringStartsWith('other-key_', $actual2);

        $actualHash1 = \substr($actual1, \strlen('some-key_'));
        $actualHash2 = \substr($actual2, \strlen('other-key_'));

        self::assertNotSame($actualHash1, $actualHash2);
    }
}
