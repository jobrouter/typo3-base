<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_base" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterBase\Tests\Unit\Domain\VariableResolvers;

use Brotkrueml\JobRouterBase\Domain\VariableResolvers\PageVariableResolver;
use Brotkrueml\JobRouterBase\Enumeration\FieldType;
use Brotkrueml\JobRouterBase\Event\ResolveFinisherVariableEvent;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

final class PageVariableResolverTest extends TestCase
{
    private PageVariableResolver $subject;
    private ServerRequestInterface & Stub $requestStub;

    protected function setUp(): void
    {
        $tsfeStub = new \stdClass();
        $tsfeStub->page = [
            'uid' => 42,
            'title' => 'some title',
            'description' => null,
        ];

        $this->subject = new PageVariableResolver();
        $this->requestStub = $this->createStub(ServerRequestInterface::class);
        $this->requestStub
            ->method('getAttribute')
            ->with('frontend.controller')
            ->willReturn($tsfeStub);
    }

    protected function tearDown(): void
    {
        unset($GLOBALS['TSFE']);
    }

    #[Test]
    #[DataProvider('dataProvider')]
    public function pageVariablesAreResolvedCorrectly(string $value, string $expected): void
    {
        $event = new ResolveFinisherVariableEvent(
            FieldType::Text,
            $value,
            '',
            [],
            $this->requestStub,
        );

        $this->subject->__invoke($event);

        self::assertSame($expected, $event->getValue());
    }

    public static function dataProvider(): iterable
    {
        yield 'Value is untouched when no page variable is used' => [
            'value' => 'some value',
            'expected' => 'some value',
        ];

        yield 'Variable is substituted when page property (string) exists' => [
            'value' => 'some {__page.title} value',
            'expected' => 'some some title value',
        ];

        yield 'Variable is substituted when page property (int) exists' => [
            'value' => 'some {__page.uid} value',
            'expected' => 'some 42 value',
        ];

        yield 'Variable is substituted when page property (null) exists' => [
            'value' => 'some {__page.description} value',
            'expected' => 'some  value',
        ];

        yield 'Two variables are substituted when page properties exist' => [
            'value' => 'some {__page.title} {__page.uid} value',
            'expected' => 'some some title 42 value',
        ];

        yield 'Variable is untouched if page property does not exist' => [
            'value' => 'some {__page.notexisting} value',
            'expected' => 'some {__page.notexisting} value',
        ];
    }
}
