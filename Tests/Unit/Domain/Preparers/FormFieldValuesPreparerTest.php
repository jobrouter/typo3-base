<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_base" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterBase\Tests\Unit\Domain\Preparers;

use Brotkrueml\JobRouterBase\Domain\Preparers\FormFieldValuesPreparer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Form\Domain\Model\FormElements\GenericFormElement;

final class FormFieldValuesPreparerTest extends TestCase
{
    private FormFieldValuesPreparer $subject;

    protected function setUp(): void
    {
        $this->subject = new FormFieldValuesPreparer();
    }

    #[Test]
    #[DataProvider('dataProviderForPrepare')]
    public function prepareForSubstitution(array $fieldElements, array $fieldsWithValues, array $expected): void
    {
        $actual = $this->subject->prepareForSubstitution($fieldElements, $fieldsWithValues);

        self::assertSame($expected, $actual);
    }

    public static function dataProviderForPrepare(): \Generator
    {
        yield 'No form values given returns form fields with empty strings' => [
            'fieldElements' => [
                'foo' => new GenericFormElement('foo', ''),
                'bar' => new GenericFormElement('bar', ''),
            ],
            'fieldsWithValues' => [],
            'expected' => [
                '{foo}' => '',
                '{bar}' => '',
            ],
        ];

        yield 'Some form values given returns correct prepared field values' => [
            'fieldElements' => [
                'foo' => new GenericFormElement('foo', ''),
                'bar' => new GenericFormElement('bar', ''),
            ],
            'fieldsWithValues' => [
                'bar' => 'some bar',
            ],
            'expected' => [
                '{foo}' => '',
                '{bar}' => 'some bar',
            ],
        ];

        yield 'All form values given returns correct prepared field values' => [
            'fieldElements' => [
                'foo' => new GenericFormElement('foo', ''),
                'bar' => new GenericFormElement('bar', ''),
            ],
            'fieldsWithValues' => [
                'foo' => 'some foo',
                'bar' => 'some bar',
            ],
            'expected' => [
                '{foo}' => 'some foo',
                '{bar}' => 'some bar',
            ],
        ];

        yield 'Form value with array (multiple values) returns correct prepared field values' => [
            'fieldElements' => [
                'foo' => new GenericFormElement('foo', ''),
            ],
            'fieldsWithValues' => [
                'foo' => ['qux', '"qoo"'],
            ],
            'expected' => [
                '{foo}' => 'qux,"""qoo"""',
            ],
        ];
    }
}
