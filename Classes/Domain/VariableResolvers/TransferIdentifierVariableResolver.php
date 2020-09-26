<?php
declare(strict_types=1);

/*
 * This file is part of the "jobrouter_base" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterBase\Domain\VariableResolvers;

use Brotkrueml\JobRouterBase\Enumeration\FieldTypeEnumeration;
use Brotkrueml\JobRouterBase\Event\ResolveFinisherVariableEvent;
use Brotkrueml\JobRouterBase\Exception\VariableResolverException;

/**
 * @internal
 */
final class TransferIdentifierVariableResolver
{
    private const VARIABLE_TO_RESOLVE = '{__transferIdentifier}';

    public function __invoke(ResolveFinisherVariableEvent $event): void
    {
        $this->checkValidFieldTypes($event->getFieldType());

        $event->setValue(
            \str_replace(self::VARIABLE_TO_RESOLVE, $event->getTransferIdentifier(), $event->getValue())
        );
    }

    private function checkValidFieldTypes(int $fieldType): void
    {
        if (FieldTypeEnumeration::TEXT === $fieldType) {
            return;
        }

        throw new VariableResolverException(
            \sprintf(
                'The "%s" variable can only be used in Text fields ("%d"), type "%d" used',
                self::VARIABLE_TO_RESOLVE,
                FieldTypeEnumeration::TEXT,
                $fieldType
            ),
            1582654966
        );
    }
}
