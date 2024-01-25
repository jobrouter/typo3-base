<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_base" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace JobRouter\AddOn\Typo3Base\Domain\VariableResolvers;

use JobRouter\AddOn\Typo3Base\Enumeration\FieldType;
use JobRouter\AddOn\Typo3Base\Event\ResolveFinisherVariableEvent;
use JobRouter\AddOn\Typo3Base\Exception\VariableResolverException;

/**
 * @internal
 */
final class CorrelationIdVariableResolver
{
    private const VARIABLE_TO_RESOLVE = '{__correlationId}';

    public function __invoke(ResolveFinisherVariableEvent $event): void
    {
        $this->checkValidFieldTypes($event->getFieldType());

        $event->setValue(
            \str_replace(self::VARIABLE_TO_RESOLVE, $event->getCorrelationId(), (string)$event->getValue()),
        );
    }

    private function checkValidFieldTypes(FieldType $fieldType): void
    {
        if ($fieldType === FieldType::Text) {
            return;
        }

        throw new VariableResolverException(
            \sprintf(
                'The "%s" variable can only be used in "Text" fields, type "%s" used',
                self::VARIABLE_TO_RESOLVE,
                $fieldType->name,
            ),
            1582654966,
        );
    }
}
