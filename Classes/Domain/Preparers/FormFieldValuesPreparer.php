<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_base" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterBase\Domain\Preparers;

use TYPO3\CMS\Form\Domain\Model\FormElements\FormElementInterface;

/**
 * @internal Only for usage in the TYPO3 JobRouter extensions!
 */
final class FormFieldValuesPreparer
{
    /**
     * @param array<string, FormElementInterface> $fieldElements
     * @param array<string, string|list<string>> $fieldsWithValues
     * @return array<string, mixed>
     */
    public function prepareForSubstitution(array $fieldElements, array $fieldsWithValues): array
    {
        \array_walk($fieldElements, static function (&$element): void {
            $element = '';
        });
        $fieldsWithValues = \array_merge($fieldElements, $fieldsWithValues);

        $preparedFieldValues = [];
        foreach ($fieldsWithValues as $name => $value) {
            $preparedFieldValues[\sprintf('{%s}', $name)]
                = \is_array($value) ? $this->convertArrayToCsv($value) : $value;
        }

        return $preparedFieldValues;
    }

    /**
     * @param list<string> $values
     */
    private function convertArrayToCsv(array $values): string
    {
        $fp = \fopen('php://memory', 'r+');
        if ($fp === false) {
            throw new \RuntimeException('Error opening php://memory', 1635177287);
        }
        if (\fputcsv($fp, $values) === false) {
            return '';
        }
        \rewind($fp);

        $csv = \stream_get_contents($fp);
        if ($csv === false) {
            throw new \RuntimeException('Cannot retrieve CSV content', 1635177288);
        }

        return \trim($csv);
    }
}
