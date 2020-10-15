<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_base" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterBase\Domain\Preparers;

/**
 * @internal Only for usage in the TYPO3 JobRouter extensions!
 */
final class FormFieldValuesPreparer
{
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

    private function convertArrayToCsv(array $values): string
    {
        $fp = \fopen('php://memory', 'r+');
        if (\fputcsv($fp, $values) === false) {
            return '';
        }
        \rewind($fp);

        return \trim(\stream_get_contents($fp));
    }
}
